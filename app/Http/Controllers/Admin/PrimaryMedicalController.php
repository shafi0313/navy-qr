<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Application;
use Yajra\DataTables\Facades\DataTables;

class PrimaryMedicalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
            $applications = Application::with([
                'examMark:id'
            ])->select('id', 'candidate_designation', 'serial_no', 'name', 'eligible_district', 'is_medical_pass');

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('serial_no', function ($row) {
                    return $row->serial_no;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('medical', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 5])) {
                        return result($row->is_medical_pass);
                    } else {
                        return '';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= "<button type='button' class='btn btn-primary btn-sm me-1' onclick='pMPass(" . $row->id . ")'>Fit</button>";
                    $btn .= "<button type='button' class='btn btn-danger btn-sm' onclick='pMFail(" . $row->id . ")'>Unfit</button>";
                    return $btn;
                })
                // ->filter(function ($query) use ($request) {
                //     if ($request->has('gender') && $request->gender != '') {
                //         $query->where('gender', $request->gender);
                //     }
                //     if ($search = $request->get('search')['value']) {
                //         $query->search($search);
                //     }
                // })
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
