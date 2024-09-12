<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ApplicationUrl;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class FinalMedicalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
            $applications = ApplicationUrl::with([
                'application:id,application_url_id,post,batch,roll,name',
                'application.examMark:id'
            ])->select('id', 'url', 'is_final_pass');

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('roll', function ($row) {
                    return $row->application ? $row->application->roll : '';
                })
                ->addColumn('name', function ($row) {
                    return $row->application ? $row->application->name : '';
                })
                ->addColumn('url', function ($row) {
                    return "<a href='$row->url' target='_blank'>Form</a>";
                })
                ->addColumn('medical', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 3])) {
                        return result($row->is_final_pass);
                    } else {
                        return '';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= "<button type='button' class='btn btn-primary btn-sm me-2' onclick='fMPass(".$row->id.")'>Pass</button>";
                    $btn .= "<button type='button' class='btn btn-danger btn-sm' onclick='fMFail(".$row->id.")'>Fail</button>";
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
                ->rawColumns(['url', 'medical', 'action'])
                ->make(true);
        }
        return view('admin.final-medical.index');
    }

    public function pass(Request $request)
    {
        $application = ApplicationUrl::find($request->id);
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
        $application = ApplicationUrl::find($request->id);
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
