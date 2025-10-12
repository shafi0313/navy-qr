<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;
use App\Models\ImportantApplication;
use Yajra\DataTables\Facades\DataTables;

class ImportantApplicationController extends Controller
{
    use ApplicationTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
            $query = Application::query();

            switch ($roleId) {
                case 1: // Supper Admin
                    $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                        ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->select(
                            array_merge($this->userColumns(), $this->applicationColumnsForResult(), $this->examColumns(), ['applications.ssc_gpa'])
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->where('is_important', 1)
                        ->orderBy('total_marks', 'desc');
                    break;
                case 2: // Admin
                    $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                        ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->select(
                            array_merge($this->userColumns(), $this->applicationColumnsForResult(), $this->examColumns(), ['applications.ssc_gpa'])
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->where('is_important', 1)
                        ->where('users.team', user()->team)
                        ->orderBy('total_marks', 'desc');
                    break;
            }
            $applications = $query;

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('exam_date', function ($row) {
                    return bdDate($row->exam_date);
                })
                ->addColumn('dob', function ($row) {
                    return bdDate($row->dob);
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
                ->addColumn('total_viva', function ($row) use ($roleId) {
                    return $this->viva($roleId, $row);
                })
                ->addColumn('remark', function ($row) {
                    $total_marks = $row->bangla + $row->english + $row->math + $row->science + $row->general_knowledge;
                    $failCount = 0;
                    $failedSubjects = [];

                    // Check each subject mark and count fails
                    if ($row->bangla < 8) {
                        $failCount++;
                        $failedSubjects['Bangla'] = $row->bangla;
                    }
                    if ($row->english < 8) {
                        $failCount++;
                        $failedSubjects['English'] = $row->english;
                    }
                    if ($row->math < 8) {
                        $failCount++;
                        $failedSubjects['Math'] = $row->math;
                    }
                    if ($row->science < 8) {
                        $failCount++;
                        $failedSubjects['Science'] = $row->science;
                    }
                    if ($row->general_knowledge < 8) {
                        $failCount++;
                        $failedSubjects['General Knowledge'] = $row->general_knowledge;
                    }

                    // If no subject failed and all marks are >= 8, it's a pass
                    if ($failCount == 0) {
                        if ($row->is_final_pass == 1) {
                            return 'Assalamulaikum Sir,</br></br>Roll No:'.$row->serial_no.', Name:'.$row->name.',  Branch:'.$row->candidate_designation.'</br></br>Candidate passed through preliminary medical, screening and appeared written exam.
                            </br></br>Passed in written exam. </br></br>Medically Fit. Recommended for Merit List. </br></br>With profound regards… </br>DPS';
                        } else {
                            return 'Assalamulaikum Sir,</br></br>Roll No:'.$row->serial_no.', Name:'.$row->name.',  Branch:'.$row->candidate_designation.'</br></br>Candidate passed through preliminary medical, screening and appeared written exam.
 </br></br>Passed in written exam. </br></br>Medically Not Fit ('.$row->f_m_remark.') </br></br>Result: Not Qualified for Viva. </br></br>With profound regards… </br>DPS';
                        }
                    }

                    // If there are any fails, list the failed subjects with their marks
                    elseif ($failCount > 0) {
                        $failedSubjectsList = '';
                        foreach ($failedSubjects as $subject => $mark) {
                            $failedSubjectsList .= $subject.' ('.$mark.'), ';
                        }
                        $failedSubjectsList = rtrim($failedSubjectsList, ', '); // Remove trailing comma and space

                        return 'Assalamulaikum Sir,</br></br>Roll No:'.$row->serial_no.', Name:'.$row->name.',  Branch:'.$row->candidate_designation.'</br></br>Candidate passed through preliminary medical, screening and appeared written exam.
 </br></br>Failed in written exam ('.$failCount.' sub)</br>'.$failedSubjectsList.' (Pass Mark 8) </br></br>Result: Not Qualified for Medical and Viva. </br></br>With profound regards… </br>DPS';
                    } else {
                        return '';
                    }
                })
                ->filter(function ($query) use ($request) {
                    if ($request->filled('district')) {
                        $query->where('applications.eligible_district', $request->district);
                    }
                    if ($request->filled('exam_date')) {
                        $query->where('applications.exam_date', $request->exam_date);
                    }if ($request->filled('team')) {
                        $query->where('users.team', $request->team);
                    }
                    if ($search = $request->get('search')['value']) {
                        $query->search($search);
                    }
                })
                ->rawColumns(['medical', 'written', 'final', 'viva', 'remark'])
                ->make(true);
        }
        $writtenMarks = ImportantApplication::paginate(20);

        return view('admin.important-application.index', compact('writtenMarks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
        ]);

        $application = Application::find($request->application_id);
        // $application->is_medical_pass = 1;
        $application->is_important = 1;
        $application->save();

        return response()->json([
            'message' => 'Application is marked as All documents held.',
        ]);
    }
}
