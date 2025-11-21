<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation error.', ['error' => $validator->errors()]);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            if ($user->is_active == 0) {
                return $this->sendError('User deactivated', ['error' => 'Your account is deactivated. Please contact admin.']);
                // exit;
            }
            $userNameLastDigit = preg_match('/\d$/', $user->name, $matches) ? "-{$matches[0]}" : '';
            $roleMap = [
                6 => 'Primary Medical ',
                7 => 'Gate Entry ',
            ];
            $role = ($roleMap[$user->role_id] ?? '').$user->team.$userNameLastDigit;

            $success['token'] = $user->createToken('auto_token')->plainTextToken;
            $success['name'] = $user->name;
            $success['user_id'] = $user->id;
            $success['role_id'] = $user->role_id;
            $success['role'] = $role;
            $success['team'] = $user->team;

            return $this->sendResponse($success, 'User login successfully.');
        } else {
            return $this->sendError('Unauthorized.', ['error' => 'User id or password do not match.']);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse([], 'Successfully logged out.');
    }
}
