<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class LockScreenController extends Controller
{
    public function locked()
    {
        session(['is_locked' => now()]);
        if (session('is_locked') == null) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.locked');
    }

    public function unlock(Request $request)
    {
        Session::forget('is_locked');
        $check = Hash::check($request->input('password'), auth()->user()->password);

        if (! $check) {
            return redirect()->route('login.locked')->withErrors([
                'Your password does not match your profile.',
            ]);
        }

        // $request->session->forget('is_locked');
        return redirect()->route('admin.dashboard');
    }
}
