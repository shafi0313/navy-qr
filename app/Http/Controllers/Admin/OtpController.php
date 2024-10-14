<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    public function showOtpForm()
    {
        if (!session('otp_required')) {
            return redirect()->route('login');
        }

        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:4',
        ]);

        $user = User::find(session('login.id'));

        if (!$user || $user->otp_expires_at < now()) {
            return redirect()->route('login')->with('error', 'OTP expired or invalid');
        }

        if ($user->otp == $request->otp) {
            // OTP is valid, log in the user
            Auth::login($user);
            session()->forget(['otp_required', 'login.id']);

            return redirect()->route('dashboard'); // or wherever you'd like
        }

        return redirect()->route('otp.form')->with('error', 'Invalid OTP');
    }
}
