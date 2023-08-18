<?php

use App\Http\Controllers\ExcelController;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::controller(MailController::class)->group(function () {
    Route::get('/send-mail',  'index');
    Route::get('/call-back','sendMail')->name('send_mail');
});

Route::get('/export-emails', [ExcelController::class, 'exportEmails']);




