<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Http\JsonResponse;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('auto_token')->plainTextToken;
            $success['name'] =  $user->name;
            return $this->sendResponse($success, 'User login successfully.');
        }else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
    // {
    //     if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
    //         $user = Auth::user();
    //         $success['token'] =  $user->createToken('MyApp')->plainTextToken;
    //         $success['name'] =  $user->name;
    //         return $this->sendResponse($success, 'User login successfully.');
    //     }else{
    //         return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
    //     }
    // }
}
