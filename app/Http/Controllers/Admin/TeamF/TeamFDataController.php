<?php

namespace App\Http\Controllers\Admin\TeamF;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\TeamFData;
use App\Traits\ApplicationTrait;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class TeamFDataController extends Controller
{
    use ApplicationTrait;

    public function index(Request $request)
    {
        if (! in_array(user()->role_id, [1, 2, 8])) {
            Alert::error('You are not authorized to perform this action');

            return back();
        }
        if ($request->ajax()) {
            $roleId = user()->role_id;
            $query = Application::where('is_team_f', 1);
            $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                ->select(
                    array_merge(
                        $this->userColumns(),
                        $this->examColumns(),
                        $this->sscResultColumns(),
                        [
                            'applications.id',
                            'applications.exam_date',
                            'applications.br_code',
                            'applications.candidate_designation',
                            'applications.serial_no',
                            'applications.eligible_district',
                            'applications.name',
                            'applications.ssc_group',
                            'applications.hsc_dip_group',
                        ]
                    )
                )->selectRaw(
                    $this->examSumColumns()
                );

            $applications = $query;

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('eligible_district', function ($row) {
                    return ucfirst($row->eligible_district);
                })
                ->addColumn('br_code', function ($row) {
                    return config('var.brCodes')[$row->br_code] ?? '';
                })
                ->addColumn('exam_date', function ($row) {
                    return bdDate($row->exam_date);
                })
                ->addColumn('written', function ($row) use ($roleId) {
                    return $this->written($roleId, $row);
                })
                ->addColumn('written_mark', function ($row) {
                    return $this->writtenMark($row);
                })
                ->addColumn('total_viva', function ($row) use ($roleId) {
                    return $this->viva($roleId, $row);
                })
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.team-f-datum.edit', $row->id), 'row' => $row]);
                    $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.team-f-datum.destroy', $row->id), 'row' => $row, 'src' => 'dt']);

                    return $btn;
                })
                ->filter(function ($query) use ($request) {
                    if ($request->filled('district')) {
                        $query->where('applications.eligible_district', $request->district);
                    }
                    if ($request->filled('ssc_group')) {
                        $query->where('applications.ssc_group', $request->ssc_group);
                    }
                    if ($request->filled('candidate_designation')) {
                        $query->where('applications.candidate_designation', $request->candidate_designation);
                    }
                    if ($request->filled('team')) {
                        $query->where('users.team', $request->team);
                    }
                    if ($search = $request->get('search')['value']) {
                        $query->search($search);
                    }
                })
                ->rawColumns(['written_mark', 'action'])
                ->make(true);
        }
        $teamFDatum = TeamFData::paginate(20);

        return view('admin.team-f.datum.index', compact('teamFDatum'));
    }

    public function edit(Request $request, $applicant_id)
    {
        if (! in_array(user()->role_id, [1, 2, 8])) {
            Alert::error('You are not authorized to perform this action');

            return back();
        }

        $applicant = Application::with(['examMark'])->findOrFail($applicant_id);

        if ($request->ajax()) {
            $modal = view('admin.team-f.datum.edit', ['applicant' => $applicant])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(403, 'Unauthorized action.');
    }

    public function update(Request $request, $applicant_id)
    {
        if (! in_array(user()->role_id, [1, 2, 8])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }

        $application = Application::findOrFail($applicant_id);

        $data = $request->validate([
            'name' => 'required|string|max:128',
            'permanent_phone' => 'required|string|max:20',
            'eligible_district' => 'required|string|max:128',

            'ssc_bangla' => 'required|string|min:0|max:64',
            'ssc_english' => 'required|string|min:0|max:64',
            'ssc_math' => 'required|string|min:0|max:64',
            'ssc_physics' => 'nullable|string|min:0|max:64',
            'ssc_biology' => 'nullable|string|min:0|max:64',
            'ssc_gpa' => 'required|string|min:0|max:8',
            'hsc_dip_gpa' => 'nullable|string|min:0|max:8',

            'local_no' => 'required|string|max:191',
            'doc_submitted' => 'nullable|string',
            'doc_submitted_to_bns' => 'nullable|string',
        ]);

        try {
            $application->update($data);

            return response()->json(['message' => 'The information has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);

            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }

    public function destroy($id)
    {
        if (! in_array(user()->role_id, [1])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }

        try {
            $application = Application::findOrFail($id);
            $application->update(['is_team_f' => null]);

            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
