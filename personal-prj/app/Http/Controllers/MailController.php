<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

    public function index(): \Illuminate\Http\RedirectResponse
    {
        $start = hrtime(true);

        // Thực hiện các tác vụ cần đo thời gian
        $path = database_path('data\group_member.csv'); // Đường dẫn đến tệp Csv

        $columnIndex = 1; // Chỉ số cột bạn muốn lấy dữ liệu từ (chỉ số bắt đầu từ 1)
        $allEmails = [];

        if (($handle = fopen($path, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if (isset($data[$columnIndex - 1])) {
                    $allEmails[] = $data[$columnIndex - 0];
                }
            }
            fclose($handle);
        }

        array_shift($allEmails);
        $allEmails = array_filter($allEmails);

        $chunkSize = 10; // Số lượng phần tử trong mỗi phần

        collect($allEmails)->chunk($chunkSize)->each(function ($chunk) {
            $chunk->each(function ($mail){
            $mailData = [
                'title' => 'Mail from kudoNghia',
                'body' => 'This is for testing email using smtp.'
            ];

            Mail::to($mail)->send(new SendMail($mailData));
            if(env('MAIL_HOST', false) === 'sandbox.smtp.mailtrap.io'){
                usleep(500000); //use usleep(500000) for half a second or less
            }
        });
        });

        $end = hrtime(true);
        $executionTime = ($end - $start) / 1e9; // Chia cho 1e9 để đổi thành giây

        // Redirect with a flash message and execution time
        return redirect()->route('send_mail')
            ->with('flash_message', 'Send message successfully.
            Thời gian gửi email: ' . $executionTime . ' giây');
    }

    public function sendMail()
    {


        if (session()->has('flash_message')) {
            $flashMessage = session('flash_message');
            return view('emails.call-back')->with('flash_message', $flashMessage);
        }

        return view('emails.call-back');
    }
}
