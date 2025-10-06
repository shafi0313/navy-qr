<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Services\RemoveSailorDataService;
use Illuminate\Http\Request;

class RemoveDataController extends Controller
{
    protected $password = '##Zxc1234';

    public function index()
    {
        return view('admin.rm.index');
    }

    public function sailor(Request $request, RemoveSailorDataService $service)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        if ($request->password !== $this->password) {
            return 'Usuccess.';
        }

        try {
            $service->remove();
            Alert::error('error', 'error');
        } catch (\Exception $e) {
        }

        return back();
    }
}
