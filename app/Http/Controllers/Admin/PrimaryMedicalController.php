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
            )->whereNotNull('scanned_at');
            if ($roleId != 1) {
                $applications->whereHas('user', function ($query) {
                    $query->where('users.team', user()->team);
                });
            }
            $applications->orderBy('exam_date')->orderBy('serial_no', 'asc');

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

        $application = Application::findOrFail($request->application_id);

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
}
