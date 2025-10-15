<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Traits\ApplicationTrait;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class ApplicationController extends Controller
{
    use ApplicationTrait;

    public function index(Request $request)
    {
        if (! in_array(user()->role_id, [1])) {
            Alert::error('You are not authorized to perform this action');

            return back();
        }
        if ($request->ajax()) {
            $roleId = user()->role_id;
            $query = Application::orderBy('exam_date')->leftJoin('users', 'applications.user_id', '=', 'users.id')
                ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                ->select(
                    array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                )
                ->selectRaw(
                    $this->examSumColumns()
                );
            if ($roleId != 1) {
                $query->where('users.team', user()->team);
            }
            $query->orderBy('total_marks', 'desc');
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
                    if ($request->filled('team')) {
                        $query->where('users.team', $request->team);
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
