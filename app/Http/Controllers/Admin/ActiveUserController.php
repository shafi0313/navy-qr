<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Laravel\Sanctum\PersonalAccessToken;

class ActiveUserController extends Controller
{
    public function __construct()
    {
        if (! in_array(user()->role_id, [1])) {
            abort(403, 'You are not authorized to perform this action');
        }
    }

    public function activeUsers()
    {
        // 🔹 Fortify sessions
        $sessions = DB::table('sessions')->get()->map(function ($session) {
            $user = User::find($session->user_id);

            return [
                'type' => 'web',
                'user_id' => $session->user_id,
                'name' => $user?->name,
                'email' => $user?->email,
                'team' => $user?->team,
                // 'ip_address' => $session->ip_address,
                'last_activity' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });

        // 🔹 Sanctum tokens
        $tokens = PersonalAccessToken::with('tokenable')->get()->map(function ($token) {
            $user = $token->tokenable;

            return [
                'type' => 'api',
                'user_id' => $token->tokenable_id,
                'name' => $user?->name,
                'email' => $user?->email,
                'team' => $user?->team,
                // 'ip_address' => $token->ip ?? null,
                'last_activity' => optional($token->last_used_at ?? $token->created_at)->diffForHumans(),
            ];
        });

        // 🔹 Merge both
        $activeUsers = $sessions->merge($tokens)->filter(fn ($u) => $u['user_id'] != null)->values();

        return view('admin.user.active-user.index', compact('activeUsers'));
    }

    public function logoutUser($id)
    {
        // 🔹 Logout from Fortify sessions
        DB::table('sessions')->where('user_id', $id)->delete();

        // 🔹 Logout from Sanctum tokens
        PersonalAccessToken::where('tokenable_id', $id)->delete();

        return back()->with('success', 'User logged out successfully!');
    }
}
