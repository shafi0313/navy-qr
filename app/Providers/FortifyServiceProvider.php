<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\LoginResponse;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

// use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        // $this->app->singleton(LoginResponseContract::class, LoginResponse::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::loginView(function () {
            Session::forget('is_locked');

            return view('auth.login');
        });

        Fortify::verifyEmailView(function () {
            return view('auth.verify');
        });

        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });

        Fortify::resetPasswordView(function ($request) {
            return view('auth.passwords.reset', ['token' => $request->token, 'request' => $request]);
        });

        Fortify::confirmPasswordView(function () {
            return view('auth.passwords.confirm');
        });

        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });

        // Fortify::authenticateUsing(function (Request $request) {
        //     // Determine if the login field is an email or username
        //     $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        //     // Find the user by the email or username
        //     $user = User::where($fieldType, $request->email)->first();

        //     // Check if the user exists
        //     if (!$user) {
        //         return false; // User not found
        //     }

        //     // Check if the account is inactive
        //     if ($user->is_active == 0) {
        //         session()->flash('error', 'Account is inactive');
        //         return false; // Prevent login for inactive users
        //     }

        //     // Check if the user has the role that requires OTP (e.g., admin roles)
        //     if (in_array($user->role_id, [1])) {
        //         // Check if the password is correct
        //         if (Hash::check($request->password, $user->password)) {
        //             // Generate a random OTP
        //             $otp = rand(1000, 9999);
        //             $user->otp = $otp;
        //             $user->otp_expires_at = now()->addMinutes(5);
        //             $user->save();

        //             // Send OTP via SMS using a helper function
        //             // $isSent = sendOtpViaSms($user->mobile, $otp);
        //             $isSent = '1234';
        //             if (!$isSent) {
        //                 session()->flash('error', 'Failed to send OTP');
        //                 return false; // Prevent login if OTP sending failed
        //             }

        //             // Store the login session and return false to prevent auto-login
        //             session(['login.id' => $user->id]);
        //             session()->flash('otp_required', true); // Indicate that OTP is needed
        //             return redirect()->route('two-factor.login');
        //             // return false; // Prevent login and redirect for OTP
        //         }
        //     } else {
        //         // Standard user login without OTP
        //         if (Hash::check($request->password, $user->password)) {
        //             return $user; // Successful login
        //         }
        //     }

        //     // If password check failed, return false
        //     return false;
        // });

        Fortify::authenticateUsing(function (Request $request) {
            // Determine if the login field is an email or username
            $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            // Find the user by the email or username
            $user = User::where($fieldType, $request->email)->first();

            // Check if the user exists
            if (! $user) {
                return false; // User not found
            }

            // Check if the account is inactive
            if ($user->is_active == 0) {
                session()->flash('error', 'Account is inactive');

                return false; // Prevent login for inactive users
            }

            // Check if the user has the role that requires OTP (e.g., admin roles)
            if (in_array($user->role_id, [1])) {
                // Check if the password is correct
                if (Hash::check($request->password, $user->password)) {
                    // Generate a random OTP
                    $otp = rand(1000, 9999);
                    $user->otp = $otp;
                    $user->otp_expires_at = now()->addMinutes(5);
                    $user->save();

                    // Simulate sending OTP via SMS
                    // Replace the following line with the actual SMS sending logic
                    // $isSent = sendOtpViaSms($user->mobile, $otp);
                    $isSent = $otp; // Placeholder for successful OTP sending

                    if (! $isSent) {
                        session()->flash('error', 'Failed to send OTP');

                        return false; // Prevent login if OTP sending failed
                    }

                    // Store the login session and return false to prevent auto-login
                    session(['login.id' => $user->id, 'otp_required' => true]);
                    // session(['login.id' => $user->id]);
                    // session()->flash('otp_required', true); // Indicate that OTP is needed

                    return redirect()->route('otp.form');

                    // return false; // Prevent user login, OTP verification will be required
                }
            } else {
                // Standard user login without OTP
                if (Hash::check($request->password, $user->password)) {
                    return $user; // Successful login
                }
            }

            // If password check failed, return false
            return false;
        });

    }
}
