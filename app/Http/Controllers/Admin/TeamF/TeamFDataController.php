<?php

namespace App\Http\Controllers\Admin\TeamF;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Traits\ApplicationTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TeamFDataController extends Controller
{
    use ApplicationTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $roleId = user()->role_id;
            $query = Application::where('is_team_f', 1);
            $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                ->select(
                    array_merge(
                        $this->userColumns(),
                        [
                            'applications.id',
                            'applications.candidate_designation',
                            'applications.serial_no',
                            'applications.eligible_district',
                            'applications.name',
                            'applications.ssc_group',
                        ]
                    )
                );
            if ($roleId != 1) {
                $query->where('team', user()->team);
            }

            $applications = $query;

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('eligible_district', function ($row) {
                    return ucfirst($row->eligible_district);
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
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.team-f.datum.index');
    }

    public function destroy($id)
    {        
        try {
            $application = Application::findOrFail($id);
            $application->update(['is_team_f' => 0]);
            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
