<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Traits\ApplicationTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ResultController extends Controller
{
    use ApplicationTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
            $query = Application::whereHas('examMark', function ($query) {
                $query->where('dup_test', '=', 'no');
            });

            if ($roleId == 1) {
                $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                    ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge(
                            $this->userColumns(),
                            $this->applicationColumnsForResult(),
                            $this->examColumns(),
                            $this->sscResultColumns(),
                            ['applications.is_important']
                        )
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->where('exam_marks.viva', '>=', 5)
                    ->where('is_final_pass', 1)
                    ->orderBy('is_medical_pass', 'desc')
                    ->orderBy('is_final_pass', 'desc')
                    ->orderBy('total_marks', 'desc')
                    ->orderBy('total_viva', 'desc');
            } else {
                $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                    ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge(
                            $this->userColumns(),
                            $this->applicationColumnsForResult(),
                            $this->examColumns(),
                            $this->sscResultColumns(),
                            ['applications.is_important']
                        )
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->where('exam_marks.viva', '>=', 5)
                    ->where('is_final_pass', 1)
                    ->where('users.team', user()->team)
                    ->orderBy('is_medical_pass', 'desc')
                    ->orderBy('is_final_pass', 'desc')
                    ->orderBy('total_marks', 'desc')
                    ->orderBy('total_viva', 'desc');
            }

            $applications = $query;

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('candidate_designation', function ($row) {
                    return str_replace('/', "/\n", $row->candidate_designation);
                })
                ->addColumn('exam_date', function ($row) {
                    return bdDate($row->exam_date);
                })
                ->addColumn('dob', function ($row) {
                    return bdDate($row->dob);
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
                ->addColumn('written_mark', function ($row) {

                    return $this->writtenMark($row);
                })
                ->addColumn('final', function ($row) use ($roleId) {
                    return $this->finalMedical($roleId, $row);
                })
                ->addColumn('total_viva', function ($row) use ($roleId) {
                    return $this->viva($roleId, $row);
                })
                ->addColumn('viva_remark', function ($row) {
                    return ($row->is_important == 1 ? '<span class="badge text-bg-primary">All doc. held</span>' : '').$row->viva_remark;
                })
                // ->addColumn('specialty', function ($row) {
                //     return '';
                // })
                ->filter(function ($query) use ($request) {
                    if ($request->filled('district')) {
                        $query->where('applications.eligible_district', $request->district);
                    }
                    if ($request->filled('ssc_gpa')) {
                        $query->where('applications.ssc_gpa', $request->ssc_gpa);
                    }
                    if ($request->filled('ssc_group')) {
                        $query->where('applications.ssc_group', $request->ssc_group);
                    }
                    if ($request->filled('candidate_designation')) {
                        $query->where('applications.candidate_designation', $request->candidate_designation);
                    }
                    if ($request->filled('dob')) {
                        $query->where('applications.dob', $request->dob);
                    }
                    if ($request->filled('height')) {
                        $query->where('applications.height', $request->height);
                    }
                    if ($request->filled('exam_date')) {
                        $query->where('applications.exam_date', $request->exam_date);
                    }
                    if ($request->filled('team')) {
                        $query->where('users.team', $request->team);
                    }
                    if ($request->filled('is_important')) {
                        $query->where('is_important', $request->is_important);
                    }
                    if ($search = $request->get('search')['value']) {
                        $query->search($search);
                    }
                })
                ->rawColumns(['ssc_result', 'medical', 'written_mark', 'written', 'final', 'viva', 'viva_remark', 'action'])
                ->make(true);
        }

        return view('admin.result.index');
    }
}
