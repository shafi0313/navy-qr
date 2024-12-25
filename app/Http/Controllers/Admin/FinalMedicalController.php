<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Traits\ApplicationTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FinalMedicalController extends Controller
{
    use ApplicationTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
            if ($roleId == 1) {
                $applications = Application::leftJoin('users', 'applications.user_id', '=', 'users.id')
                    ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->where(function ($query) {
                        $query->where('bangla', '>=', 8)
                            ->where('english', '>=', 8)
                            ->where('math', '>=', 8)
                            ->where('science', '>=', 8)
                            ->where('general_knowledge', '>=', 8);
                    });
            } else {
                $applications = Application::leftJoin('users', 'applications.user_id', '=', 'users.id')
                    ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->where(function ($query) {
                        $query->where('bangla', '>=', 8)
                            ->where('english', '>=', 8)
                            ->where('math', '>=', 8)
                            ->where('science', '>=', 8)
                            ->where('general_knowledge', '>=', 8);
                    })
                    ->where('team', user()->team);
            }

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('exam_date', function ($row) {
                    return bdDate($row->exam_date);
                })
                ->addColumn('eligible_district', function ($row) {
                    return ucfirst($row->eligible_district);
                })
                ->addColumn('medical', function ($row) use ($roleId) {
                    return $this->primaryMedical($roleId, $row);
                })
                ->addColumn('written', function ($row) use ($roleId) {
                    return $this->written($roleId, $row);
                })
                ->addColumn('final', function ($row) use ($roleId) {
                    return $this->finalMedical($roleId, $row);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= view('button', ['type' => 'fit', 'route' => route('admin.final_medicals.fit', $row->id), 'row' => $row]);
                    $btn .= view('button', ['type' => 'unfit', 'route' => route('admin.final_medicals.unfit', $row->id), 'row' => $row]);

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
                ->rawColumns(['medical', 'written', 'final', 'action'])
                ->make(true);
        }

        return view('admin.final-medical.index');
    }

    // public function pass(Request $request)
    // {
    //     if (!in_array(user()->role_id, [1, 2, 3])) {
    //         return response()->json(['message' => 'You are not authorized to perform this action'], 403);
    //     }
    //     $application = Application::find($request->id);
    //     $application->height = $request->height .'\''. $request->height .'\"';
    //     $application->save();
    //     try {
    //         $application->save();
    //         return response()->json(['message' => 'The status has been updated'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
    //     }
    // }

    public function fitModal(Request $request, Application $application)
    {
        if ($request->ajax()) {
            $modal = view('admin.final-medical.fit')->with(['application' => $application])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }

    public function fitStore(Request $request)
    {
        if (! in_array(user()->role_id, [1, 2, 3])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        $application = Application::find($request->id);
        try {
            $application->update([
                'is_final_pass' => 1,
                'height' => $application->height = $request->height.'\''.$request->height.'"',
                'f_m_remark' => null,
            ]);

            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    public function unfitModal(Request $request, Application $application)
    {
        if ($request->ajax()) {
            $modal = view('admin.final-medical.unfit')->with(['application' => $application])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }

    public function unfitStore(Request $request)
    {
        if (! in_array(user()->role_id, [1, 2, 3])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        $application = Application::find($request->id);
        try {
            $application->update([
                'is_final_pass' => 0,
                'f_m_remark' => $request->f_m_remark,
            ]);

            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }
}
