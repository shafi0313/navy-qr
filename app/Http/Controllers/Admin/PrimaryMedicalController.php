<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SendSmsJob;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class PrimaryMedicalController extends Controller
{
    use ApplicationTrait;

    public function index(Request $request)
    {
        if (!in_array(user()->role_id, [1, 2, 6])) {
            Alert::error('Access Denied', 'You are not authorized to perform this action');
            return back();
        }
        if ($request->ajax()) {
            $roleId = user()->role_id;
            if ($roleId == 1) {
                // For roleId 1: Fetch applications without filtering by team
                $applications = Application::with([
                    'examMark:id,application_id',
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
                                1 => '<span class="btn btn-success btn-sm">Fit</span><br> ' . ($row->is_important == 1 ? '(All documents held)' : ''),
                                0 => '<span class="btn btn-danger btn-sm">Unfit </span> ' . ($row->p_m_remark ? '(' . $row->p_m_remark . ')' : ''),
                            };
                        } else {
                            return '<span class="btn btn-warning btn-sm">Pending</span><br> ' . ($row->is_important == 1 ? '(All documents held)' : '');
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= "<button type='button' class='btn btn-primary btn-sm me-1' onclick='pMPass(" . $row->id . ")'>Fit</button>";
                    // $btn .= "<button type='button' class='btn btn-danger btn-sm' onclick='pMFail(" . $row->id . ")'>Unfit</button>";
                    $btn .= view('button', ['type' => 'unfit', 'route' => route('admin.primary_medicals.unfit', $row->id), 'row' => $row]);

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
        if (! in_array(user()->role_id, [1, 2, 6])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }

        $application = Application::findOrFail($request->id);
        if ($application->user_id == null) {
            $application->update(['user_id' => user()->id, 'scanned_at' => now()]);
        }
        $application->is_medical_pass = 1;
        $application->save();
        try {
            $application->save();

            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    public function unfitModal(Request $request, Application $application)
    {
        if ($request->ajax()) {
            if (! in_array(user()->role_id, [1, 2, 6])) {
                return response()->json(['message' => 'You are not authorized to perform this action'], 403);
            }

            $modal = view('admin.primary-medical.unfit')->with(['application' => $application])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }

    public function unfitStore(Request $request)
    {
        if (! in_array(user()->role_id, [1, 2, 6])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        
        $application = Application::find($request->id);

        try {
            if ($application->user_id == null) {
                $application->update(['user_id' => user()->id, 'scanned_at' => now()]);
            }
            $application->update([
                'is_medical_pass' => 0,
                'p_m_remark' => $request->p_m_remark,
            ]);

            if (env('APP_DEBUG') == false) {
                $msg = 'The status has been updated';
                $type = 'Primary Medical';
                SendSmsJob::dispatch(user()->id, $application->current_phone, $msg, $type)->onQueue('default');
            }

            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }
}
