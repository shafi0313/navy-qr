<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VivaMarkController extends Controller
{
    use ApplicationTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
                $applications = Application::with(['examMark:id,application_id,bangla,english,math,science,general_knowledge,viva'])
                ->whereHas('application.examMark', function ($query) {
                    $query->where('bangla', '>=', 8)
                        ->where('english', '>=', 8)
                        ->where('math', '>=', 8)
                        ->where('science', '>=', 8)
                        ->where('general_knowledge', '>=', 8);
                })->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                ->select(
                    $this->applicationColumns(),
                    $this->examColumns(),
                )
                ->selectRaw(
                    $this->examSumColumns()
                )
                ->orderBy('total_viva', 'desc')
                ->orderBy('total_marks', 'desc');

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('exam_date', function ($row) {
                    return bdDate($row->exam_date);
                })
                ->addColumn('eligible_district', function ($row) {
                    return ucfirst($row->eligible_district);
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
                ->addColumn('action', function ($row) {
                    $btn = '';
                    // $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.exam_marks.modal_store', $row->id), 'row' => $row]);
                    $btn .= view('button', ['type' => 'ajax-add-by-id', 'route' => route('admin.exam_marks.modal_store', $row->id), 'row' => $row]);

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
            'application_id' => ['required', 'exists:applications,id'],
            'viva'           => ['required', 'numeric'],
        ]);
        $application = Application::select('id', 'serial_no', 'name', 'is_final_pass')->whereId($request->application_id)->first();

        if ($application->is_final_pass != 1) {
            return response()->json(['message' => 'Please update final medical first'], 404);
        }

        try {
            $application->update($data);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }
}
