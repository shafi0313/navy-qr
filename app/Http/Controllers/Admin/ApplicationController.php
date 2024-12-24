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

            if ($roleId == 1) {
                $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                    ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->orderBy('total_marks', 'desc');
            } else {
                $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                    ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->where('team', user()->team)
                    ->orderBy('total_marks', 'desc');
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
