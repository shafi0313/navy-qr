<?php

use App\Http\Controllers\Auth\OtpController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.front-login');
})->name('index');

// Route::get('/two-factor-login', [AuthController::class, 'showTwoFactorLogin'])->name('two-factor.login');
// Route::post('/two-factor-login', [AuthController::class, 'verifyTwoFactorLogin'])->name('two-factor.verify');
// Route::middleware(['otp'])->group(function () {
//     Route::get('/otp', [OtpController::class, 'showOtpForm'])->name('otp.form');
//     Route::post('/two-factor-login', [OtpController::class, 'verifyOtp'])->name('verify_otp');
// });

Route::get('/login', [OtpController::class, 'showLoginForm'])->name('login');
Route::post('/otp/login', [OtpController::class, 'login'])->name('otp.login');
Route::get('/otp/form', [OtpController::class, 'showOtpForm'])->name('otp.form');
Route::post('/otp/verify', [OtpController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/logout', [OtpController::class, 'logout'])->name('logout');
