<?php

use App\Http\Controllers\ExcelController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\RegisterController;
use Illuminate\Routing\Router;
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

//Route::get('/', function () {
//    return view('welcome');
//});

/**
 * Mail Routes
 */
Route::controller(MailController::class)->group(function () {
    Route::post('/send-mail', 'index');
    Route::get('/call-back', 'sendMail')->name('send_mail');
});

Route::get('/export-emails', [ExcelController::class, 'exportEmails']);

/**
 * Home Routes
 */

Route::get('/', [HomeController::class, 'index'])->name('home.index');

/**
 * Register Routes
 */
Route::middleware([
    'guest',
])->group(function () {
    /**
     * Register Routes
     */
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'show')->name('register.show');
        Route::post('/register', 'register')->name('register.perform');
    });

    Route::post('/files', [FileController::class, 'upload'])->name('file.upload');

    /**
     * Login Routes
     */
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'show')->name('login.show');
        Route::post('/login', 'login')->name('login.perform');
    });
});

Route::middleware([
    'auth'
])->group(function () {
    /**
     * Logout Routes
     */
    Route::controller(LogoutController::class)->group(function () {
        Route::get('/logout', 'perform')->name('logout.perform');
    });
});



