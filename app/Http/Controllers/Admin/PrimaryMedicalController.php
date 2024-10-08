<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class PrimaryMedicalController extends Controller
{
    use ApplicationTrait;
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
            $applications = Application::with([
                'examMark:id'
            ])->select('id', 'candidate_designation', 'exam_date', 'serial_no', 'name', 'eligible_district', 'is_medical_pass', 'remark');

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('exam_date', function ($row){
                    return bdDate($row->exam_date);
                })
                ->addColumn('eligible_district', function ($row){
                    return ucfirst($row->eligible_district);
                })
                ->addColumn('medical', function ($row) use ($roleId) {
                    return $this->primaryMedical($roleId, $row);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= "<button type='button' class='btn btn-primary btn-sm me-1' onclick='pMPass(" . $row->id . ")'>Fit</button>";
                    $btn .= "<button type='button' class='btn btn-danger btn-sm' onclick='pMFail(" . $row->id . ")'>Unfit</button>";
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
                ->rawColumns(['medical', 'written', 'final', 'viva', 'action'])
                ->make(true);
        }
        return view('admin.primary-medical.index');
    }

    public function pass(Request $request)
    {
        $application = Application::find($request->id);
        $application->is_medical_pass = 1;
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
        $application = Application::find($request->id);
        $application->is_medical_pass = 0;
        $application->save();
        try {
            $application->save();
            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }
}
