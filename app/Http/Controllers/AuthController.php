<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showTwoFactorLogin()
    {
        return view('auth.two-factor-challenge');
    }

    public function verifyTwoFactorLogin(Request $request)
    {
        $userId = session('login.id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors('Invalid session');
        }

        // Check if OTP matches and is not expired
        if ($user->otp == $request->otp && Carbon::now()->lessThanOrEqualTo($user->otp_expires_at)) {
            // OTP is valid, log in the user
            Auth::login($user);
            session()->forget('login.id');
            return redirect()->route('dashboard');
        }

        return redirect()->route('two-factor.login')->withErrors('Invalid or expired OTP');
    }
}
