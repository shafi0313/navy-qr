<?php

namespace App\Http\Controllers;

use RealRashid\SweetAlert\Facades\Alert;


abstract class Controller
{
    protected function authorize(
        $permission,
        $error_message = 'Don\'t have permission to perform this action',
    ) {
        if (! user()->can($permission)) {
            Alert::error('Error', $error_message);
            return redirect()->back();
            // return redirect()->back()->withInput()->withErrors($error_message);
        }
    }
}
