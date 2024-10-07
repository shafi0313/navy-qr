<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Hash;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Support\Facades\RateLimiter;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Http\Responses\LoginResponse;
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
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

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

        // Custom Login Validation
        Fortify::authenticateUsing(function (Request $request) {
            $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $user = User::where($fieldType, $request->email)->first();
            if ($user && $user->is_active == 0) {
                return redirect()->route('login')->withErrors('Account is inactive');
            }
            
            if ($user && Hash::check($request->password, $user->password)) {
                return $user;
            }
        });

        //     // Custom Login Validation
        //     Fortify::authenticateUsing(function (Request $request) {
        //         $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        //         $user = User::where($fieldType, $request->email)->first();

        //         if ($user && $user->is_active == 0) {
        //             return redirect()->route('login')->withErrors('Account is inactive');
        //         }

        //         if ($user && Hash::check($request->password, $user->password)) {
        //             // Generate a random OTP
        //             $otp = rand(1000, 9999);
        //             $user->otp = $otp;
        //             $user->otp_expires_at = now()->addMinutes(5);
        //             $user->save();

        //             // Send OTP via SMS using SmsHelper
        //             $isSent = sendOtpViaSms($user->phone_number, $otp);
        //             if (!$isSent) {
        //                 return redirect()->route('login')->withErrors('Failed to send OTP');
        //             }

        //             // Store the login session and redirect to OTP input form
        //             session(['login.id' => $user->id]);
        //             return redirect()->route('two-factor.login');
        //         }

        //         return false;
        //     });
    }
}
