<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ExamMark;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ResetDataController extends Controller
{
    public function index()
    {
        return view('admin.reset-data.index');
    }

    public function show($id)
    {
        $applicant = Application::with('examMark')->whereId($id)->first();
        if (is_null($applicant)) {
            return response()->json(['success' => false, 'message' => 'No applicant found'], 404);
        }
        $modal = view('admin.reset-data.data')->with(['applicant' => $applicant])->render();

        return response()->json(['success' => true, 'modal' => $modal], 200);
    }

    public function store(Request $request)
    {
        $application = Application::with('examMark')->findOrFail($request->id);

        // Simple checkboxes → null
        $simpleCheckboxes = [
            'scanned_at',
            'is_important',
        ];

        foreach ($simpleCheckboxes as $field) {
            if ($request->has($field)) {
                $application->$field = null;
            }
        }

        // Checkboxes with remarks → null
        $checkboxesWithRemarks = [
            'is_medical_pass' => 'p_m_remark',
            'is_final_pass' => 'f_m_remark',
        ];

        $application->user_id = null;

        foreach ($checkboxesWithRemarks as $field => $remark) {
            if ($request->has($field)) {
                $application->$field = null;
                $application->$remark = null;
            }
        }

        // Delete ExamMark if all checkboxes present
        if ($request->has(['written_exam', 'viva', 'dup_test']) && $application->examMark->exists) {
            $application->examMark->delete();
        }

        // Reset ExamMark fields if record exists
        if ($application->examMark->exists) {
            $examMark = $application->examMark;

            if ($request->has('written_exam')) {
                $examMark->fill([
                    'bangla' => null,
                    'english' => null,
                    'math' => null,
                    'science' => null,
                    'general_knowledge' => null,
                    'total' => null,
                ]);
            }

            if ($request->has('viva')) {
                $examMark->fill([
                    'viva' => null,
                    'viva_remark' => null,
                ]);
            }

            if ($request->has('dup_test')) {
                $examMark->dup_test = null;
            }

            $examMark->save();
        }

        $application->save();

        Alert::success('Success', 'The information has been updated');

        return back();
    }
}
