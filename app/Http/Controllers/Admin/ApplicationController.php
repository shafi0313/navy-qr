<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\ApplicationUrl;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        // $query = Application::query();

        // return $query->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
        // ->select(
        //     'applications.id',
        //     'applications.candidate_designation',
        //     'applications.serial_no',
        //     'applications.eligible_district',
        //     'applications.name',
        //     'applications.father_name',
        //     'applications.mother_name',
        //     'applications.current_phone',
        //     'applications.is_medical_pass',
        //     'applications.is_final_pass',
        //     // 'applications.',
        //     'exam_marks.bangla',
        //     'exam_marks.english',
        //     'exam_marks.math',
        //     'exam_marks.science',
        //     'exam_marks.general_knowledge',
        // )
        // ->selectRaw(
        //     '(exam_marks.bangla +
        //     exam_marks.english +
        //     exam_marks.math +
        //     exam_marks.science +
        //     exam_marks.general_knowledge) as total_marks'
        // )
        // ->orderBy('total_marks', 'desc')->get();


        if ($request->ajax()) {
            // return($request->district);
            $roleId = user()->role_id;
            $query = Application::query();

            switch ($roleId) {
                case 1: // Admin
                    $query->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
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
                            'applications.remark',

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
                    break;
                case 2: // Normal User
                    $query->select('id', 'candidate_designation', 'serial_no', 'name');
                    break;
                case 3: // Primary Medical
                    $query->select('id', 'candidate_designation', 'serial_no', 'name','is_medical_pass');
                    break;
                case 4: // Written
                    $query->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
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
                    break;
                case 5: // Final Medical
                    $query->with(['examMark:id,application_id,bangla,english,math,science,general_knowledge'])
                        ->whereHas('examMark', function ($query) {
                            $query->where('bangla', '>=', 8)
                                ->where('english', '>=', 8)
                                ->where('math', '>=', 8)
                                ->where('science', '>=', 8)
                                ->where('general_knowledge', '>=', 8);
                        })
                        ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
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
                    break;
                case 6: // Viva / Final Selection
                    $query->with(['examMark:id,application_id,bangla,english,math,science,general_knowledge,viva'])
                        ->whereHas('application.examMark', function ($query) {
                            $query->where('bangla', '>=', 8)
                                ->where('english', '>=', 8)
                                ->where('math', '>=', 8)
                                ->where('science', '>=', 8)
                                ->where('general_knowledge', '>=', 8);
                        })->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
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
                            exam_marks.general_knowledge) as total_marks,
                            exam_marks.viva as total_viva'
                        )
                        ->orderBy('total_viva', 'desc')
                        ->orderBy('total_marks', 'desc');
                    break;
            }
            $applications = $query;

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('serial_no', function ($row) {
                    return $row->serial_no;
                })
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('medical', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 3, 4, 5, 6])) {
                        return result($row->is_medical_pass);
                    } else {
                        return '';
                    }
                })
                ->addColumn('written', function ($row) use ($roleId) {
                    return $row->total_marks;
                    if (in_array($roleId, [1, 4, 5, 6])) {
                        $written = $row;
                        $totalMark = $written->bangla + $written->english + $written->math + $written->science + $written->general_knowledge;
                        if ($written->bangla >= 8 && $written->english >= 8 && $written->math >= 8 && $written->science >= 8 && $written->general_knowledge >= 8) {
                            return '<span class="badge bg-success">Pass</span>' . ' ' . ($row->total_marks ? $row->total_marks : '');
                        } elseif ($totalMark > 2 && ($written->bangla <= 7 || $written->english <= 7 || $written->math <= 7 || $written->science <= 7 || $written->general_knowledge <= 7)) {
                            return '<span class="badge bg-danger">Fail</span>';
                        } else {
                            return '<span class="badge bg-warning">Pending</span>';
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('final', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 5, 6])) {
                        return result($row->is_final_pass);
                    } else {
                        return '';
                    }
                })
                ->addColumn('viva', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 5, 6])) {
                        return $row->viva;
                    } else {
                        return '';
                    }
                })
                // ->addColumn('action', function ($row) {
                //     $btn = '';
                //     if (userCan('admin-edit')) {
                //         $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.admins.edit', $row->id), 'row' => $row]);
                //     }
                //     if (userCan('admin-delete')) {
                //         $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.admins.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                //     }
                //     return $btn;
                // })
                // ->filter(function ($query) use ($request) {
                //     if ($request->has('gender') && $request->gender != '') {
                //         $query->where('gender', $request->gender);
                //     }
                //     if ($request->has('district') && $request->district != '') {
                //         $query->where('applications.eligible_district', $request->district);
                //     }
                //     if ($search = $request->get('search')['value']) {
                //         $query->search($search);
                //     }
                // })
                ->rawColumns(['url', 'medical', 'written', 'final', 'viva', 'action'])
                ->make(true);
        }

        return view('admin.application.index');
    }
}
