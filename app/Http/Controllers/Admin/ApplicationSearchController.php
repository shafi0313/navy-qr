<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Traits\ApplicationTrait;
use Illuminate\Http\Request;

class ApplicationSearchController extends Controller
{
    use ApplicationTrait;

    public function index()
    {
        return view('admin.application-search.index');
    }

    public function show($id)
    {
        $data = Application::whereId($id)->get();

        if ($data) {
            return response()->json(['success' => true, 'data' => $data]);
        } else {
            return response()->json(['success' => false, 'message' => 'Data not found'], 404);
        }
    }

    public function store(Request $request)
    {
        $application = Application::where('id', $request->application_id)->first();
        try {
            $application->update(['user_id' => user()->id, 'scanned_at' => now()]);

            return response()->json(['message' => 'The information has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
