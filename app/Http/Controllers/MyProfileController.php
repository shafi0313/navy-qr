<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateAdminUserRequest;

class MyProfileController extends Controller
{
    public function index()
    {
        return view('admin.user.my-profile.index');
    }

    public function edit()
    {
        $myProfile = user();
        $genders = config('var.genders');
        return view('admin.user.my-profile.edit', compact('myProfile', 'genders'));
    }
}
