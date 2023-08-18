<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmailExport; // Tạo class EmailExport ở bước tiếp theo

class ExcelController extends Controller
{
    public function exportEmails()
    {
        return Excel::download(new EmailExport(), 'emails.xlsx');
    }
}

