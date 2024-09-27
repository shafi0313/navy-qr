<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VivaMarkController extends Controller
{
    public function create()
    {
        return view('admin.viva-mark.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'application_id' => ['required', 'exists:applications,id'],
            'viva'           => ['required', 'numeric'],
        ]);
        $application = Application::select('id', 'serial_no', 'name', 'is_final_pass')->whereId($request->application_id)->first();

        if ($application->is_final_pass != 1) {
            return response()->json(['message' => 'Please update final medical first'], 404);
        }

        try {
            $application->update($data);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }
}
