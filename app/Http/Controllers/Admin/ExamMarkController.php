<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExamMark;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreExamMarkRequest;
use App\Http\Requests\UpdateExamMarkRequest;

class ExamMarkController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
            $applications = Application::leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                ->select(
                    'applications.id',
                    'applications.candidate_designation',
                    'applications.serial_no',
                    'applications.eligible_district',
                    'applications.name',
                    'applications.father_name',
                    'applications.mother_name',
                    'applications.current_phone',
                    'applications.is_medical_pass',
                    'applications.is_final_pass',
                    // 'applications.',
                    'exam_marks.bangla',
                    'exam_marks.english',
                    'exam_marks.math',
                    'exam_marks.science',
                    'exam_marks.general_knowledge',
                )
                ->selectRaw(
                    '(exam_marks.bangla +
                exam_marks.english +
                exam_marks.math +
                exam_marks.science +
                exam_marks.general_knowledge) as total_marks'
                )
                ->orderBy('total_marks', 'desc');

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('serial_no', function ($row) {
                    return $row->serial_no;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('written', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 4, 5, 6]) && ($row->bangla || $row->english || $row->math || $row->science || $row->general_knowledge)) {
                        $row->bangla + $row->english + $row->math + $row->science + $row->general_knowledge;
                        $failCount = 0;
                        // Check each subject mark and count fails
                        if ($row->bangla < 8) $failCount++;
                        if ($row->english < 8) $failCount++;
                        if ($row->math < 8) $failCount++;
                        if ($row->science < 8) $failCount++;
                        if ($row->general_knowledge < 8) $failCount++;
                        // If no subject failed and all marks are >= 8, it's a pass
                        if ($failCount == 0) {
                            return '<span class="badge bg-success">Pass</span>';
                        }
                        // If there are any fails, it's a fail
                        elseif ($failCount > 0) {
                            return '<span class="badge bg-danger">Failed</span> (' . $failCount . ' subject(s) failed)';
                        } else {
                            return '';
                        }
                    } else {
                        return '';
                    }
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

        if ($check->is_medical_pass != 1) {
            return response()->json(['message' => 'Please update primary medical first'], 404);
        }

        $data['created_by'] = user()->id;

        try {
            ExamMark::updateOrCreate(['id' => $request->application_id], $data);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
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
    public function edit(ExamMark $examMark)
    {
        //
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
