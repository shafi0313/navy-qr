<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\OtpController;

Route::get('/', function () {
    return view('auth.front-login');
});

// Route::get('/two-factor-login', [AuthController::class, 'showTwoFactorLogin'])->name('two-factor.login');
// Route::post('/two-factor-login', [AuthController::class, 'verifyTwoFactorLogin'])->name('two-factor.verify');
Route::middleware(['otp'])->group(function () {
    Route::get('/otp', [OtpController::class, 'showOtpForm'])->name('otp.form');
    Route::post('/two-factor-login', [OtpController::class, 'verifyOtp'])->name('verify_otp');
});
