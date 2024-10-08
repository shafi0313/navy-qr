<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class ApplicationController extends Controller
{
    use ApplicationTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
            $query = Application::query();
            switch ($roleId) {
                case 1: // Supper Admin
                    $query->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->select(
                            array_merge($this->applicationColumns(), $this->examColumns())
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->orderBy('total_marks', 'desc');
                    break;
                case 2: // Admin
                    $query->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->select(
                            array_merge($this->applicationColumns(), $this->examColumns())
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->where('team', user()->team)
                        ->orderBy('total_marks', 'desc');
                    break;
                case 3: // Viva / Final Selection
                    $query->with(['examMark:id,application_id,bangla,english,math,science,general_knowledge,viva'])
                        ->whereHas('application.examMark', function ($query) {
                            $query->where('bangla', '>=', 8)
                                ->where('english', '>=', 8)
                                ->where('math', '>=', 8)
                                ->where('science', '>=', 8)
                                ->where('general_knowledge', '>=', 8);
                        })->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->select(
                            array_merge($this->applicationColumns(), $this->examColumns())
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->where('team', user()->team)
                        ->orderBy('total_viva', 'desc')
                        ->orderBy('total_marks', 'desc');
                    break;
                case 4: // Final Medical
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
                            array_merge($this->applicationColumns(), $this->examColumns())
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->where('team', user()->team)
                        ->orderBy('total_marks', 'desc');
                    break;
                case 5: // Written
                    $query->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                        ->select(
                            array_merge($this->applicationColumns(), $this->examColumns())
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->where('team', user()->team)
                        ->orderBy('total_marks', 'desc');
                    break;

                case 6: // Primary Medical
                    $query->select('id', 'candidate_designation', 'serial_no', 'name', 'eligible_district', 'is_medical_pass')
                        ->where('team', user()->team);
                    break;
                case 7: // Normal User
                    $query->select('id', 'candidate_designation', 'serial_no', 'name', 'eligible_district');
                    break;
            }
            $applications = $query;

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('exam_date', function ($row) {
                    return bdDate($row->exam_date);
                })
                ->addColumn('eligible_district', function ($row) {
                    return ucfirst($row->eligible_district);
                })
                ->addColumn('medical', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 3, 4, 5, 6])) {
                        return result($row->is_medical_pass);
                    } else {
                        return '';
                    }
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

        return view('admin.application.index');
    }
}
