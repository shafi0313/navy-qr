<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExamMark;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreExamMarkRequest;
use App\Http\Requests\UpdateExamMarkRequest;

class ExamMarkController extends Controller
{
    use ApplicationTrait;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
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

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('exam_date', function ($row) {
                    return bdDate($row->exam_date);
                })
                ->addColumn('eligible_district', function ($row) {
                    return ucfirst($row->eligible_district);
                })
                ->addColumn('written', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 4, 5, 6]) && ($row->bangla || $row->english || $row->math || $row->science || $row->general_knowledge)) {
                        $row->bangla + $row->english + $row->math + $row->science + $row->general_knowledge;
                        $failCount = 0;
                        if ($row->bangla < 8) $failCount++;
                        if ($row->english < 8) $failCount++;
                        if ($row->math < 8) $failCount++;
                        if ($row->science < 8) $failCount++;
                        if ($row->general_knowledge < 8) $failCount++;
                        if ($failCount == 0) {
                            return '<span class="badge bg-success">Pass</span>';
                        }
                        elseif ($failCount > 0) {
                            return '<span class="badge bg-danger">Failed</span> (' . $failCount . ' subject(s) failed)';
                        } else {
                            return '';
                        }
                    } else {
                        return '';
                    }
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
                ->rawColumns(['medical', 'written', 'final', 'viva', 'action'])
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
        $check = Application::select('id', 'serial_no', 'name', 'is_medical_pass')->whereId($request->application_id)->first();

        if (!$check) {
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

    /**
     * Display the specified resource.
     */
    public function show(ExamMark $examMark)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, ExamMark $examMark)
    {
        if ($request->ajax()) {
            $modal = view('admin.exam-mark.edit')->with(['exa$examMark' => $examMark])->render();
            return response()->json(['modal' => $modal], 200);
        }
        return abort(500);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExamMarkRequest $request, ExamMark $examMark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamMark $examMark)
    {
        //
    }
}
