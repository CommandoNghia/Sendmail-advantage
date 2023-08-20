<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index(): \Illuminate\Http\RedirectResponse
    {
        $start = hrtime(true);

        $path = database_path('data\users.json');

//        $fileJson = file_get_contents($path);
//        $result = json_decode($fileJson, true);


        // Thực hiện các tác vụ cần đo thời gian

        $chunkSize = 200; // Số lượng phần tử trong mỗi phần

        $users = User::all();
        $userEmails = $users->pluck('email');

        $checkEmails = collect($userEmails)->filter(function ($email) {
            return $email;
        });

        $checkEmails->chunk($chunkSize)->each(function ($chunk) {
            $chunk->each(function ($mail) {
                $mailData = [
                    'title' => 'Mail from kudoNghia',
                    'body' => 'This is for testing email using smtp.'
                ];

                Mail::to($mail)->send(new SendMail($mailData));
            });
        });

        $end = hrtime(true);
        $executionTime = ($end - $start) / 1e9; // Chia cho 1e9 để đổi thành giây

        // Redirect with a flash message and execution time
        return redirect()->route('send_mail')
            ->with('flash_message', 'Send message successfully.
            Thời gian gửi email: ' . $executionTime . ' giây');
    }

    /**
     * Display the email sending page.
     *
     * If a flash message is present in the session, pass it to the 'emails.call-back' view.
     * Otherwise, return the 'emails.call-back' view without any attached message.
     *
     * @return \Illuminate\View\View
     */
    public function sendMail()
    {
        if (session()->has('flash_message')) {
            $flashMessage = session('flash_message');
            return view('emails.call-back')->with('flash_message', $flashMessage);
        }

        return view('emails.call-back');
    }
}
