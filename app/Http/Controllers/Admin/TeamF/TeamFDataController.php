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
                            'applications.br_code',
                            'applications.candidate_designation',
                            'applications.serial_no',
                            'applications.eligible_district',
                            'applications.name',
                            'applications.ssc_group',
                        ]
                    )
                )->selectRaw(
                    $this->examSumColumns()
                );
            if ($roleId != 1) {
                $query->where('users.team', user()->team);
            }

            $applications = $query;

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('eligible_district', function ($row) {
                    return ucfirst($row->eligible_district);
                })
                ->addColumn('br_code', function ($row) {
                    return config('var.brCodes')[$row->br_code] ?? '';
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
                ->rawColumns(['written_mark','action'])
                ->make(true);
        }
        $teamFDatum = TeamFData::paginate(20);

        return view('admin.team-f.datum.index', compact('teamFDatum'));
    }

    public function destroy($id)
    {
        if (! in_array(user()->role_id, [1, 2, 8])) {
            Alert::error('You are not authorized to perform this action');

            return back();
        }
        try {
            $application = Application::findOrFail($id);
            $application->update(['is_team_f' => 0]);

            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
