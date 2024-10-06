<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class FinalMedicalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
            $applications = Application::with([
                'examMark:id'
            ])->select('id', 'candidate_designation', 'serial_no', 'name', 'eligible_district', 'is_medical_pass', 'is_final_pass')
            ->whereHas('examMark', function ($query) {
                $query->where('bangla', '>=', 8)
                    ->where('english', '>=', 8)
                    ->where('math', '>=', 8)
                    ->where('science', '>=', 8)
                    ->where('general_knowledge', '>=', 8);
            });

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('exam_date', function ($row) {
                    return bdDate($row->exam_date);
                })
                ->addColumn('eligible_district', function ($row) {
                    return ucfirst($row->eligible_district);
                })
                ->addColumn('medical', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 3, 5])) {
                        return result($row->is_medical_pass );
                    } else {
                        return '';
                    }
                })
                ->addColumn('final_medical', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 3])) {
                        return result($row->is_final_pass);
                    } else {
                        return '';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= "<button type='button' class='btn btn-primary btn-sm me-1' onclick='fMPass(" . $row->id . ")'>Fit</button>";
                    $btn .= "<button type='button' class='btn btn-danger btn-sm' onclick='fMFail(" . $row->id . ")'>Unfit</button>";
                    return $btn;
                })
                ->filter(function ($query) use ($request) {
                    if ($request->filled('district')) {
                        $query->where('applications.eligible_district', $request->district);
                    }
                    if ($request->filled('exam_date')) {
                        $query->where('applications.exam_date', $request->exam_date);
                    }
                    if ($search = $request->get('search')['value']) {
                        $query->search($search);
                    }
                })
                ->rawColumns(['medical', 'final_medical', 'action'])
                ->make(true);
        }
        return view('admin.final-medical.index');
    }

    public function pass(Request $request)
    {
        if (!in_array(user()->role_id, [1, 3])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        $application = Application::find($request->id);
        $application->is_final_pass = 1;
        $application->save();
        try {
            $application->save();
            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    public function fail(Request $request)
    {
        if (!in_array(user()->role_id, [1, 3])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        $application = Application::find($request->id);
        $application->is_final_pass = 0;
        $application->save();
        try {
            $application->save();
            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }
}
