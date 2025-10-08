<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        try {
            $application = Application::with('examMark')->findOrFail($request->id);
            $examMark = $application->examMark;

            // লগ রাখার জন্য JSON array তৈরি
            $logData = [
                'application_id' => $application->id,
                'user_id' => auth()->id(),
                'time' => now()->toDateTimeString(),
                'resets' => [], // এখানে শুধুমাত্র নির্বাচিত ফিল্ডগুলা আসবে
            ];

            // 🟢 Simple checkbox reset
            $simpleCheckboxes = ['scanned_at', 'is_important'];
            foreach ($simpleCheckboxes as $field) {
                if ($request->boolean($field)) {
                    $logData['resets'][$field] = $application->$field; // পুরনো মান রাখো
                    $application->$field = null;
                }
            }

            // 🟢 Checkbox + remark reset
            $checkboxesWithRemarks = [
                'is_medical_pass' => 'p_m_remark',
                'is_final_pass' => 'f_m_remark',
            ];

            foreach ($checkboxesWithRemarks as $field => $remark) {
                if ($request->boolean($field)) {
                    $logData['resets'][$field] = $application->$field;
                    $logData['resets'][$remark] = $application->$remark;

                    $application->$field = null;
                    $application->$remark = null;
                }
            }

            // 🟢 ExamMark reset
            if ($examMark) {
                $examResetData = [];

                // যদি সবগুলো exam checkbox select করা হয়
                if ($request->has(['written_exam', 'viva', 'dup_test'])) {
                    $examResetData = $examMark->only([
                        'bangla', 'english', 'math', 'science', 'general_knowledge', 'viva', 'dup_test', 'total',
                    ]);
                    $examMark->delete();
                    $logData['resets']['exam_mark_deleted'] = $examResetData;
                } else {
                    // আলাদা আলাদা ফিল্ড reset
                    if ($request->boolean('written_exam')) {
                        $examResetData = array_merge($examResetData, $examMark->only([
                            'bangla', 'english', 'math', 'science', 'general_knowledge', 'total',
                        ]));
                        $examMark->fill(array_fill_keys(array_keys($examResetData), null));
                    }

                    if ($request->boolean('viva')) {
                        $examResetData = array_merge($examResetData, $examMark->only(['viva', 'viva_remark']));
                        $examMark->viva = null;
                        $examMark->viva_remark = null;
                    }

                    if ($request->boolean('dup_test')) {
                        $examResetData['dup_test'] = $examMark->dup_test;
                        $examMark->dup_test = null;
                    }

                    if (! empty($examResetData)) {
                        $logData['resets']['exam_mark'] = $examResetData;
                        $examMark->save();
                    }
                }
            }

            $application->save();

            // ✅ যদি অন্তত ১টা checkbox সিলেক্ট করা হয় তাহলে লগ লিখো
            if (! empty($logData['resets'])) {
                Log::channel('application_reset')->info(json_encode($logData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            }
            Alert::success('Success', 'The information has been updated');

            return back();
        } catch (\Throwable $e) {
            Log::error('Application Update Error: '.$e->getMessage());

            return back()->withErrors('Something went wrong. Please try again.');
        }
    }
}
