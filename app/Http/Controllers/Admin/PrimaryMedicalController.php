<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Traits\ApplicationTrait;
use App\Traits\SmsTrait;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class PrimaryMedicalController extends Controller
{
    use ApplicationTrait, SmsTrait;

    public function index(Request $request)
    {
        if (! in_array(user()->role_id, [1, 2, 4, 6])) {
            Alert::error('Access Denied', 'You are not authorized to perform this action');

            return back();
        }
        if ($request->ajax()) {
            $roleId = user()->role_id;
            if ($roleId == 1) {
                $applications = Application::with([
                    'examMark:id,application_id',
                    'user:id,team',
                ])->select(
                    'id',
                    'user_id',
                    'candidate_designation',
                    'exam_date',
                    'serial_no',
                    'name',
                    'eligible_district',
                    'is_important',
                    'is_medical_pass',
                    'p_m_remark',
                    'scanned_at',
                )->whereNotNull('scanned_at');
            } else {
                $applications = Application::with([
                    'examMark:id,application_id',
                    'user:id,team',
                ])->select(
                    'id',
                    'candidate_designation',
                    'exam_date',
                    'serial_no',
                    'name',
                    'eligible_district',
                    'is_important',
                    'is_medical_pass',
                    'p_m_remark',
                    'scanned_at',
                )->whereNotNull('scanned_at')
                    ->whereHas('user', function ($query) {
                        $query->where('team', user()->team);
                    });
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
                    if (in_array($roleId, [1, 2, 3, 4, 5, 6])) {
                        $data = $row->is_medical_pass;
                        if ($data == '1' || $data == '0') {
                            $data = (int) $data;

                            return match ($data) {
                                1 => '<span class="btn btn-success btn-rem">Fit</span><br> '.($row->is_important == 1 ? '(All documents held)' : ''),
                                0 => '<span class="btn btn-danger btn-rem">Unfit </span><br> '.($row->is_important == 1 ? '(All documents held)' : '').($row->p_m_remark ? '('.$row->p_m_remark.')' : ''),
                            };
                        } else {
                            return '<span class="btn btn-warning btn-rem">Pending</span><br> '.($row->is_important == 1 ? '(All documents held)' : '');
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    // $btn .= "<button type='button' class='btn btn-primary btn-rem me-1' onclick='pMPass(" . $row->id . ")'>Fit</button>";
                    // $btn .= "<button type='button' class='btn btn-danger btn-rem' onclick='pMFail(" . $row->id . ")'>Unfit</button>";
                    // $btn .= view('button', ['type' => 'unfit', 'route' => route('admin.primary_medicals.unfit', $row->id), 'row' => $row]);
                    $btn .= view('button', ['type' => 'ajax-add-by-id', 'route' => route('admin.primary_medicals.modal_show', $row->id), 'row' => $row]);

                    return $btn;
                })
                ->filter(function ($query) use ($request) {
                    if ($request->filled('district')) {
                        $query->where('applications.eligible_district', $request->district);
                    }
                    if ($request->filled('exam_date')) {
                        $query->where('applications.exam_date', $request->exam_date);
                    }
                    if ($request->filled('is_medical_pass')) {
                        if ($request->is_medical_pass == 'null') {
                            $query->whereNull('applications.is_medical_pass');
                        } else {
                            $query->where('applications.is_medical_pass', $request->is_medical_pass);
                        }
                    }
                    if ($request->filled('team')) {
                        $query->whereHas('user', function ($q) use ($request) {
                            $q->where('team', $request->team);
                        });
                    }
                    if ($search = $request->get('search')['value']) {
                        $query->search($search);
                    }
                })
                ->rawColumns(['medical', 'written', 'final', 'viva', 'action'])
                ->make(true);
        }
        // return response()->json(['is_medical_pass' => $request->is_medical_pass]);

        return view('admin.primary-medical.index');
    }

    public function modalShow(Request $request, $applicantId)
    {
        if ($request->ajax()) {
            if (! in_array(user()->role_id, [1, 4, 5])) {
                return response()->json(['message' => 'You are not authorized to perform this action'], 403);
            }

            $applicant = Application::select('id', 'candidate_designation', 'serial_no', 'name', 'is_medical_pass', 'p_m_remark')->whereId($applicantId)->first();
            $modal = view('admin.primary-medical.fit-unfit-modal')->with(['applicant' => $applicant])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }

    public function store(Request $request)
    {
        if (! in_array(user()->role_id, [1, 4, 6])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }

        // if ($request->primary_medical == 0 && empty($request->p_m_remark)) {
        //     return response()->json(['message' => 'Please provide a remark for unfit status'], 422);
        // }

        $application = Application::findOrFail($request->application_id);

        // if ($application->is_medical_pass == 1) {
        //     return response()->json(['message' => 'The status has been updated'], 200);
        // }

        try {
            if ($application->user_id == null) {
                $application->update(['user_id' => user()->id, 'scanned_at' => now()]);
            }
            $application->update([
                'is_medical_pass' => $request->primary_medical,
                'p_m_remark' => $request->primary_medical == 0 ? $request->p_m_remark : null,
            ]);

            // // Send SMS notification
            if ($request->primary_medical == 0) {
                $this->fail($application->current_phone, 'Primary Medical');
            }

            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    // public function pass(Request $request)
    // {
    //     if (! in_array(user()->role_id, [1, 2, 6])) {
    //         return response()->json(['message' => 'You are not authorized to perform this action'], 403);
    //     }

    //     $application = Application::findOrFail($request->id);
    //     if ($application->is_medical_pass == 1) {
    //         return response()->json(['message' => 'The status has been updated'], 200);
    //     }

    //     if ($application->user_id == null) {
    //         $application->update(['user_id' => user()->id, 'scanned_at' => now()]);
    //     }

    //     $application->is_medical_pass = 1;
    //     $application->save();
    //     try {
    //         $application->save();

    //         return response()->json(['message' => 'The status has been updated'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
    //     }
    // }

    // public function unfitModal(Request $request, Application $application)
    // {
    //     if ($request->ajax()) {
    //         if (! in_array(user()->role_id, [1, 2, 6])) {
    //             return response()->json(['message' => 'You are not authorized to perform this action'], 403);
    //         }

    //         $modal = view('admin.primary-medical.unfit')->with(['application' => $application])->render();

    //         return response()->json(['modal' => $modal], 200);
    //     }

    //     return abort(500);
    // }

    // public function unfitStore(Request $request)
    // {
    //     if (! in_array(user()->role_id, [1, 2, 6])) {
    //         return response()->json(['message' => 'You are not authorized to perform this action'], 403);
    //     }

    //     $application = Application::find($request->id);

    //     // if (!empty($application->is_medical_pass) && $application->is_medical_pass == 0) {
    //     //     return response()->json(['message' => 'The status has been updated'], 200);
    //     // }

    //     try {
    //         if ($application->user_id == null) {
    //             $application->update(['user_id' => user()->id, 'scanned_at' => now()]);
    //         }
    //         $application->update([
    //             'is_medical_pass' => 0,
    //             'p_m_remark' => $request->p_m_remark,
    //         ]);

    //         $this->fail($application->current_phone, 'Primary Medical');

    //         return response()->json(['message' => 'The status has been updated'], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
    //     }
    // }
}
