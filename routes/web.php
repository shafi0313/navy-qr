<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('auth.front-login');
})->name('index');


Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/otp/login', [AuthController::class, 'login'])->name('otp.login');
Route::get('/otp/form', [AuthController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
