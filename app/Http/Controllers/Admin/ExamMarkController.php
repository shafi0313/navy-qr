<?php

namespace App\Http\Controllers\Admin;

use App\Jobs\SendSmsJob;
use App\Models\ExamMark;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreExamMarkRequest;

class ExamMarkController extends Controller
{
    use ApplicationTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
            if ($roleId == 1) {
                $applications = Application::leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->applicationColumns(), $this->examColumns())
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
                        array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
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
                ->rawColumns(['medical', 'written', 'action'])
                ->make(true);
        }

        return view('admin.exam-mark.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.exam-mark.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamMarkRequest $request)
    {
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

            if (env('APP_DEBUG') == false){
                if($request->bangla < 8 || $request->english < 8 || $request->math < 8 || $request->science < 8 || $request->general_knowledge < 8){
                    $msg = 'You have failed in written exam.';
                    $type = 'Written Exam';
                    SendSmsJob::dispatch(user()->id, $check->current_phone, $msg, $type)->onQueue('default');
                }                
            }

            return response()->json(['message' => 'The information has been inserted/updated', 'examMark' => $examMark], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    public function modalStore(Request $request, $applicantId)
    {
        if ($request->ajax()) {
            $applicant = Application::with('examMark')->select('id', 'candidate_designation', 'serial_no', 'name', 'is_medical_pass')->whereId($applicantId)->first();
            $modal = view('admin.exam-mark.add')->with(['applicant' => $applicant])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }

    public function edit(Request $request, ExamMark $examMark)
    {
        if ($request->ajax()) {
            $modal = view('admin.exam-mark.edit')->with(['exa$examMark' => $examMark])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }
}
