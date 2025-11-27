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

        // Check if user exists
        if (! $user) {
            return back()->with('error', 'Invalid credentials.');
        }

        // Check active status
        if ($user->is_active == 0) {
            return back()->with('error', 'Your account is deactivated. Please contact support.');
        }

        // Verify password
        if (! Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid credentials.');
        }

        // If 2FA enabled and app is not in debug mode
        if (! config('app.debug') && $user->is_2fa) {

            // Generate OTP
            $otp = random_int(1000, 9999); // more secure than rand()

            $user->update([
                'otp' => $otp,
                'otp_expires_at' => now()->addMinutes(5),
            ]);

            SendOtpSmsJob::dispatch($user->id, $user->mobile, $otp)
                ->onQueue('high');

            // Set required session flags
            session([
                'login.id' => $user->id,
                'otp_required' => true,
            ]);

            return redirect()->route('otp.form');
        }

        // Login without 2FA
        Auth::login($user);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Logged in successfully.');
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);

    //     $user = User::where('email', $request->email)->first();
    //     if ($user->is_active == 0) {
    //         return back()->with('error', 'Your account is deactivated. Please contact support.');
    //     }

    //     if ($user && Hash::check($request->password, $user->password)) {
    //         if (env('APP_DEBUG') == false && $user->is_2fa == true) {
    //             // Generate OTP
    //             $otp = rand(1000, 9999);
    //             $user->otp = $otp;
    //             $user->otp_expires_at = now()->addMinutes(5);
    //             $user->save();

    //             SendOtpSmsJob::dispatch($user->id, $user->mobile, $otp)->onQueue('high');
    //             // if (! $isSent) {
    //             //     return back()->with('error', 'Failed to send OTP.');
    //             // }

    //             // Store session and redirect to OTP form
    //             session(['login.id' => $user->id, 'otp_required' => true]);

    //             if (session('login.id')) {
    //                 return redirect()->route('otp.form');
    //             } else {
    //                 return redirect()->route('login')->with('error', 'Session expired. Please login again.');
    //             }

    //             return redirect()->route('otp.form');
    //         } else {
    //             Auth::login($user);

    //             return redirect()->route('admin.dashboard')->with('success', 'Logged in successfully.');
    //         }
    //     }

    //     return back()->with('error', 'Invalid credentials.');
    // }

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
