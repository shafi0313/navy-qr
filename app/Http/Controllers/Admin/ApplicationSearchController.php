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
        $applicants = Application::with('examMark')->whereId($id)->get();
        if ($applicants->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No applicants found'], 404);
        }
        $modal = view('admin.application-search.data')->with(['applicants' => $applicants])->render();

        return response()->json(['success' => true, 'modal' => $modal], 200);
    }

    public function store(Request $request)
    {
        $application = Application::where('id', $request->application_id)->first();
        try {
            if ($request->yes_no == 1) {
                $application->update(['user_id' => user()->id, 'scanned_at' => now()]);

                return response()->json(['message' => 'The information has been accepted'], 200);
            } else {
                $application->update(['scanned_at' => null]);

                return response()->json(['message' => 'The information has been rejected'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }

    public function edit(Request $request, $applicantId)
    {
        if ($request->ajax()) {
            if (! in_array(user()->role_id, [1, 2, 5])) {
                return response()->json(['message' => 'You are not authorized to perform this action'], 403);
            }

            $applicant = Application::select('id', 'candidate_designation', 'serial_no', 'name', 'is_medical_pass', 'p_m_remark')->whereId($applicantId)->first();
            $modal = view('admin.application-search.add')->with(['applicant' => $applicant])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }
}
