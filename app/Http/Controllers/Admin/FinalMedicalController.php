<?php

namespace App\Http\Controllers\Admin;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Traits\ApplicationTrait;
use App\Traits\SmsTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class FinalMedicalController extends Controller
{
    use ApplicationTrait, SmsTrait;

    public function index(Request $request)
    {
        if (! in_array(user()->role_id, [1, 2, 4])) {
            Alert::error('Access Denied', 'You are not authorized to perform this action');

            return back();
        }
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
                    ->where('applications.is_medical_pass', 1)
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
                    ->where('applications.is_medical_pass', 1)
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
                    return $this->finalMedical($roleId, $row).' Height:'.$row->height;
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    // $btn .= view('button', ['type' => 'fit', 'route' => route('admin.final_medicals.fit', $row->id), 'row' => $row]);
                    // $btn .= view('button', ['type' => 'unfit', 'route' => route('admin.final_medicals.unfit', $row->id), 'row' => $row]);
                    $btn .= view('button', ['type' => 'ajax-add-by-id', 'route' => route('admin.final_medicals.modal_show', $row->id), 'row' => $row]);

                    return $btn;
                })
                ->filter(function ($query) use ($request) {
                    if ($request->filled('district')) {
                        $query->where('applications.eligible_district', $request->district);
                    }
                    if ($request->filled('exam_date')) {
                        $query->where('applications.exam_date', $request->exam_date);
                    }
                    if ($request->filled('team')) {
                        $query->where('users.team', $request->team);
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

    public function modalShow(Request $request, $applicantId)
    {
        if ($request->ajax()) {
            if (! in_array(user()->role_id, [1, 5])) {
                return response()->json(['message' => 'You are not authorized to perform this action'], 403);
            }

            $applicant = Application::select('id', 'candidate_designation', 'serial_no', 'name', 'is_final_pass', 'p_m_remark')->whereId($applicantId)->first();
            $modal = view('admin.final-medical.fit-unfit-modal')->with(['applicant' => $applicant])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }

    public function store(Request $request)
    {
        if (! in_array(user()->role_id, [1, 6])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }

        if ($request->final_medical == 0 && empty($request->f_m_remark)) {
            return response()->json(['message' => 'Please provide a remark for unfit status'], 422);
        }

        $application = Application::findOrFail($request->application_id);

        // if ($application->is_final_pass == 1) {
        //     return response()->json(['message' => 'The status has been updated'], 200);
        // }

        try {
            if ($application->user_id == null) {
                $application->update(['user_id' => user()->id, 'scanned_at' => now()]);
            }
            $application->update([
                'is_final_pass' => $request->final_medical,
                'height' => $request->height.'\''.$request->height2.'"',
                'p_m_remark' => $request->final_medical == 0 ? $request->f_m_remark : null,
            ]);

            // // Send SMS notification
            if ($request->final_medical == 0) {
                $this->fail($application->current_phone, 'Final Medical');
            }

            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    // public function fitModal(Request $request, Application $application)
    // {
    //     if ($request->ajax()) {
    //         if (! in_array(user()->role_id, [1, 2, 4])) {
    //             return response()->json(['message' => 'You are not authorized to perform this action'], 403);
    //         }
    //         $modal = view('admin.final-medical.fit')->with(['application' => $application])->render();

    //         return response()->json(['modal' => $modal], 200);
    //     }

    //     return abort(500);
    // }

    // public function fitStore(Request $request)
    // {
    //     if (! in_array(user()->role_id, [1, 2, 4])) {
    //         return response()->json(['message' => 'You are not authorized to perform this action'], 403);
    //     }
    //     $application = Application::select('id', 'is_final_pass', 'height', 'f_m_remark')->find($request->id);

    //     // if ($application->is_final_pass == 1) {
    //     //     return response()->json(['message' => 'The status has been updated'], 200);
    //     // }

    //     try {
    //         $application->update([
    //             'is_final_pass' => 1,
    //             'height' => $request->height.'\''.$request->height2.'"',
    //             'f_m_remark' => null,
    //         ]);

    //         return response()->json(['message' => 'The status has been updated'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
    //     }
    // }

    // public function unfitModal(Request $request, Application $application)
    // {
    //     if ($request->ajax()) {
    //         if (! in_array(user()->role_id, [1, 2, 4])) {
    //             return response()->json(['message' => 'You are not authorized to perform this action'], 403);
    //         }
    //         $modal = view('admin.final-medical.unfit')->with(['application' => $application])->render();

    //         return response()->json(['modal' => $modal], 200);
    //     }

    //     return abort(500);
    // }

    // public function unfitStore(Request $request)
    // {
    //     if (! in_array(user()->role_id, [1, 2, 4])) {
    //         return response()->json(['message' => 'You are not authorized to perform this action'], 403);
    //     }
    //     $application = Application::find($request->id);
    //     // if ($application->is_final_pass == 0) {
    //     //     return response()->json(['message' => 'The status has been updated'], 200);
    //     // }
    //     try {
    //         $application->update([
    //             'is_final_pass' => 0,
    //             'f_m_remark' => $request->f_m_remark,
    //         ]);

    //         // SMS Trait Function
    //         $this->fail($application->current_phone, 'Final Medical');

    //         return response()->json(['message' => 'The status has been updated'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
    //     }
    // }
}
