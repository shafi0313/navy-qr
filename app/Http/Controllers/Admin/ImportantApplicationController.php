<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;
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
                            array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
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
                            array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                        )
                        ->selectRaw(
                            $this->examSumColumns()
                        )
                        ->where('is_important', 1)
                        ->where('team', user()->team)
                        ->orderBy('total_marks', 'desc');
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
                    return $this->primaryMedical($roleId, $row);
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
        return view('admin.important-application.index');
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
            'message' => 'Application is marked as important.',
        ]);
    }
}
