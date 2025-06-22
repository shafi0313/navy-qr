<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamMarkRequest;
use App\Models\Application;
use App\Models\ExamMark;
use App\Traits\ApplicationTrait;
use App\Traits\SmsTrait;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class ExamMarkController extends Controller
{
    use ApplicationTrait, SmsTrait;

    public function index(Request $request)
    {
        if (! in_array(user()->role_id, [1, 2, 5])) {
            Alert::error('Access Denied', 'You are not authorized to perform this action');

            return back();
        }
        if ($request->ajax()) {
            $roleId = user()->role_id;
            if ($roleId == 1) {
                $applications = Application::leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->applicationColumns(), $this->examColumns(), $this->sscResultColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->where('applications.is_medical_pass', 1)
                    ->orderBy('total_marks', 'desc')
                    ->orderBy('serial_no', 'asc');
            } else {
                $applications = Application::leftJoin('users', 'applications.user_id', '=', 'users.id')
                    ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns(), $this->sscResultColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->where('team', user()->team)
                    ->where('applications.is_medical_pass', 1)
                    ->orderBy('total_marks', 'desc')
                    ->orderBy('serial_no', 'asc');
            }

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('exam_date', function ($row) {
                    return bdDate($row->exam_date);
                })
                ->addColumn('eligible_district', function ($row) {
                    return ucfirst($row->eligible_district);
                })
                ->addColumn('ssc_result', function ($row) {
                    return '<span>'
                        .'GPA: '.$row->ssc_gpa.'<br>'
                        .$row->ssc_bangla.'<br>'
                        .$row->ssc_english.'<br>'
                        .($row->ssc_math !== null ? $row->ssc_math.'<br>' : '')
                        .($row->ssc_physics !== null ? $row->ssc_physics.'<br>' : '')
                        .$row->ssc_biology
                        .'</span>';
                })
                ->addColumn('medical', function ($row) use ($roleId) {
                    return $this->primaryMedical($roleId, $row);
                })
                ->addColumn('written', function ($row) use ($roleId) {
                    return $this->written($roleId, $row);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= view('button', ['type' => 'ajax-add-by-id', 'route' => route('admin.exam_marks.modal_store', $row->id), 'row' => $row]);

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
                ->rawColumns(['ssc_result', 'medical', 'written', 'action'])
                ->make(true);
        }

        return view('admin.exam-mark.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     return view('admin.exam-mark.create');
    // }

    public function modalStore(Request $request, $applicantId)
    {
        if ($request->ajax()) {
            if (! in_array(user()->role_id, [1, 2, 5])) {
                return response()->json(['message' => 'You are not authorized to perform this action'], 403);
            }

            $applicant = Application::with('examMark')->select('id', 'candidate_designation', 'serial_no', 'name', 'is_medical_pass')->whereId($applicantId)->first();
            $modal = view('admin.exam-mark.add')->with(['applicant' => $applicant])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamMarkRequest $request)
    {
        if (! in_array(user()->role_id, [1, 2, 5])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }

        $data = $request->validated();
        $check = Application::select('id', 'current_phone', 'serial_no', 'name', 'is_medical_pass')->whereId($request->application_id)->first();

        if (! $check) {
            return response()->json(['message' => 'Application not found'], 404);
        }

        if ($check->is_medical_pass != 1) {
            return response()->json(['message' => 'Please update primary medical first'], 404);
        }
        $data['created_by'] = user()->id;
        try {
            $examMark = ExamMark::updateOrCreate(
                ['application_id' => $request->application_id],
                $data
            );

            if ($request->bangla < 8 || $request->english < 8 || $request->math < 8 || $request->science < 8 || $request->general_knowledge < 8) {
                $this->fail($check->current_phone, 'Written');
            }

            return response()->json(['message' => 'The information has been inserted/updated', 'examMark' => $examMark], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    // public function edit(Request $request, ExamMark $examMark)
    // {
    //     if ($request->ajax()) {
    //         if (! in_array(user()->role_id, [1, 2, 5])) {
    //             return response()->json(['message' => 'You are not authorized to perform this action'], 403);
    //         }

    //         $modal = view('admin.exam-mark.edit')->with(['exa$examMark' => $examMark])->render();

    //         return response()->json(['modal' => $modal], 200);
    //     }

    //     return abort(500);
    // }
}
