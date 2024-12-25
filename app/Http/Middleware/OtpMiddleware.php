<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OtpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (session('otp_required')) {
            return redirect()->route('otp.form');
        }

        return $next($request);
    }
}
