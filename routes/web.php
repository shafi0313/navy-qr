<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('auth.front-login');
});

Route::get('/two-factor-login', [AuthController::class, 'showTwoFactorLogin'])->name('two-factor.login');
Route::post('/two-factor-login', [AuthController::class, 'verifyTwoFactorLogin'])->name('two-factor.verify');
