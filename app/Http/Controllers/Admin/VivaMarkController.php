<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Traits\ApplicationTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VivaMarkController extends Controller
{
    use ApplicationTrait;

    public function index(Request $request)
    {        
        if ($request->ajax()) {
            $roleId = user()->role_id;
            if ($roleId == 1) {
                $applications = Application::with(['examMark:id,application_id,bangla,english,math,science,general_knowledge,viva,viva_remark'])
                    ->whereHas('examMark', function ($query) {
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
                    ->where('is_final_pass', 1)
                    ->orderBy('total_viva', 'desc')
                    ->orderBy('total_marks', 'desc');
            } else {
                $applications = Application::with(['examMark:id,application_id,bangla,english,math,science,general_knowledge,viva,viva_remark'])
                    ->whereHas('examMark', function ($query) {
                        $query->where('bangla', '>=', 8)
                            ->where('english', '>=', 8)
                            ->where('math', '>=', 8)
                            ->where('science', '>=', 8)
                            ->where('general_knowledge', '>=', 8);
                    })->leftJoin('users', 'applications.user_id', '=', 'users.id')
                    ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->where('team', user()->team)
                    ->where('is_final_pass', 1)
                    ->orderBy('total_viva', 'desc')
                    ->orderBy('total_marks', 'desc');
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
                ->addColumn('final', function ($row) use ($roleId) {
                    return $this->finalMedical($roleId, $row);
                })
                ->addColumn('total_viva', function ($row) use ($roleId) {
                    return $this->viva($roleId, $row);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    // $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.exam_marks.modal_store', $row->id), 'row' => $row]);
                    $btn .= view('button', ['type' => 'ajax-add-by-id', 'route' => route('admin.viva_marks.modal_store', $row->id), 'row' => $row]);

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

        return view('admin.viva-mark.index');
    }

    public function create()
    {
        return view('admin.viva-mark.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'viva' => ['required', 'numeric', 'min:0', 'max:10'],
            'viva_remark' => ['nullable', 'string'],
        ]);
        $application = Application::select('id', 'serial_no', 'name', 'is_final_pass')->whereId($request->application_id)->first();

        if ($application->is_final_pass != 1) {
            return response()->json(['message' => 'Please update final medical first'], 404);
        }

        try {
            $application->examMark->update($data);

            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    public function modalStore(Request $request, $applicantId)
    {
        if ($request->ajax()) {
            $applicant = Application::with('examMark')->select('id', 'candidate_designation', 'serial_no', 'name', 'is_medical_pass')->whereId($applicantId)->first();
            $modal = view('admin.viva-mark.add')->with(['applicant' => $applicant])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }
}
