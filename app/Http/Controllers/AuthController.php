<?php

namespace App\Http\Controllers;

use App\Jobs\SendOtpSmsJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.front-login');
    }

    // Handle login and generate OTP
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if (env('APP_DEBUG') == false && $user->is_2fa == true) {
                // Generate OTP
                $otp = rand(1000, 9999);
                $user->otp = $otp;
                $user->otp_expires_at = now()->addMinutes(5);
                $user->save();

                // Simulate sending OTP (replace with real SMS sending logic)
                // $isSent = sendOtpViaSms($user->mobile, $otp);
                SendOtpSmsJob::dispatch($user->id, $user->mobile, $otp)->onQueue('high');
                // if (! $isSent) {
                //     return back()->with('error', 'Failed to send OTP.');
                // }

                // Store session and redirect to OTP form
                session(['login.id' => $user->id, 'otp_required' => true]);

                return redirect()->route('otp.form');
            } else {
                Auth::login($user);

                return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully.');
            }
        }

        return back()->with('error', 'Invalid credentials.');
    }

    // Show OTP form
    public function showOtpForm()
    {
        if (! session('otp_required')) {
            return redirect()->route('index')->with('error', 'Unauthorized access.');
        }
        $otpExpiresAt = User::find(session('login.id'))->otp_expires_at;

        return view('auth.otp-form', compact('otpExpiresAt'));
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:4',
        ]);

        $user = User::find(session('login.id'));

        if (! $user) {
            return redirect()->route('login')->with('error', 'Invalid session.');
        }

        if ($user->otp == (int) $request->otp && $user->otp_expires_at > now()) {
            // Log the user in
            Auth::login($user);

            // Clear session data
            session()->forget(['login.id', 'otp_required']);

            return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully.');
        }

        return back()->with('error', 'Invalid or expired OTP.');
    }

    // Handle logout
    public function logout()
    {
        Auth::logout();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
