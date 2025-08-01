<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Traits\ApplicationTrait;
use App\Traits\SmsTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class VivaMarkController extends Controller
{
    use ApplicationTrait, SmsTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
            if ($roleId == 1) {
                $applications = Application::with(['examMark:id,application_id,bangla,english,math,science,general_knowledge,viva,dup_test,viva_remark'])
                    ->whereHas('examMark', function ($query) {
                        $query->where('bangla', '>=', 8)
                            ->where('english', '>=', 8)
                            ->where('math', '>=', 8)
                            ->where('science', '>=', 8)
                            ->where('general_knowledge', '>=', 8);
                    })->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->applicationColumns(), $this->examColumns(), $this->sscResultColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->where('applications.is_medical_pass', 1)
                    ->where('is_final_pass', 1)
                    ->orderBy('total_viva', 'desc')
                    ->orderBy('total_marks', 'desc');
            } else {
                $applications = Application::with(['examMark:id,application_id,bangla,english,math,science,general_knowledge,viva,dup_test,viva_remark'])
                    ->whereHas('examMark', function ($query) {
                        $query->where('bangla', '>=', 8)
                            ->where('english', '>=', 8)
                            ->where('math', '>=', 8)
                            ->where('science', '>=', 8)
                            ->where('general_knowledge', '>=', 8);
                    })->leftJoin('users', 'applications.user_id', '=', 'users.id')
                    ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns(), $this->sscResultColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->where('team', user()->team)
                    ->where('applications.is_medical_pass', 1)
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
                ->addColumn('written_mark', function ($row) {
                    return '<span>'
                            .'Bangla: '.$row->bangla.'<br>'
                            .'English: '.$row->english.'<br>'
                            .'Math: '.$row->math.'<br>'
                            .'Science: '.$row->science.'<br>'
                            .'GK: '.$row->general_knowledge
                        .'</span>';
                })
                ->addColumn('written', function ($row) use ($roleId) {
                    return $this->written($roleId, $row);
                })
                ->addColumn('final', function ($row) use ($roleId) {
                    return $this->finalMedical($roleId, $row).' Height:'.$row->height;
                })
                ->addColumn('total_viva', function ($row) use ($roleId) {
                    return $this->viva($roleId, $row);
                })
                ->addColumn('dup_test', function ($row) {
                    if ($row->examMark->dup_test) {
                        return $row->examMark->dup_test == 'yes' ? 'Pos' : 'Neg';
                    }
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
                ->rawColumns(['ssc_result', 'medical', 'written_mark', 'written', 'final', 'viva', 'action'])
                ->make(true);
        }

        return view('admin.viva-mark.index');
    }

    // public function create()
    // {
    //     return view('admin.viva-mark.create');
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'viva' => ['required', 'numeric', 'min:0', 'max:10'],
            'viva_remark' => ['nullable', 'string'],
            'dup_test' => ['nullable', 'in:yes,no'],
        ]);
        $application = Application::select('id', 'serial_no', 'current_phone', 'name', 'is_final_pass')->whereId($request->application_id)->first();

        if ($application->is_final_pass != 1) {
            return response()->json(['message' => 'Please update final medical first'], 404);
        }

        $application->examMark->viva;

        try {
            // Check and send SMS if necessary
            $currentViva = $application->examMark->viva ?? null;

            if ($currentViva !== null && $currentViva != $data['viva'] && $data['viva'] < 5) {
                $this->fail($application->current_phone, 'Viva');
            } elseif ($currentViva === null && $data['viva'] < 5) {
                $this->fail($application->current_phone, 'Viva');
            }

            $application->examMark->update($data);

            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    public function modalStore(Request $request, $applicantId)
    {
        if ($request->ajax()) {
            $applicant = Application::with('examMark')
                ->select('id', 'candidate_designation', 'serial_no', 'name', 'is_medical_pass')
                ->whereId($applicantId)
                ->first();

            $modal = view('admin.viva-mark.add')->with(['applicant' => $applicant])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(500);
    }
}
