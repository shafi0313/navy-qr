<?php

namespace App\Http\Controllers\Admin\TeamF;

use App\Models\TeamFData;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Imports\TeamFDataImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class TeamFImportDataController extends Controller
{
    public function index()
    {
        $teamFDatum = TeamFData::paginate(20);

        return view('admin.team-f.import-data.index', compact('teamFDatum'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        try {
            Excel::import(new TeamFDataImport, $request->file('file'));
            Alert::success('Mark imported successfully!');
        } catch (\Exception $e) {
            return $e->getMessage();
            Alert::error('Something went wrong!, Please try again.');
        }

        return back();
    }

    public function singleStoreView(Request $request)
    {
        if (! in_array(user()->role_id, [1, 2])) {
            return response()->json(['message' => 'You cannot edit'], 403);
        }

        if ($request->ajax()) {
            $applicant = Application::select('id', 'name', 'serial_no', 'candidate_designation')->find($request->id);
            $modal = view('admin.team-f.import-data.single-store-modal', ['applicant' => $applicant])->render();

            return response()->json(['modal' => $modal], 200);
        }

        return abort(403, 'Unauthorized action.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function singleStore(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'team_f' => 'required|in:0,1',
        ]);

        if ($request->team_f == 0) {
            return response()->json(['message' => 'Application is not marked as Team F.'], 200);
        }

        $application = Application::find($request->application_id);
        $application->is_team_f = 1;
        $application->br_code = $request->br_code ?? null;
        $application->save();

        return response()->json([
            'message' => 'Application is marked as Team F.',
        ], 200);
    }

    public function store()
    {
        try {
            $importantApplications = TeamFData::select('id', 'serial_no', 'br_code')->get();
            $serialNos = $importantApplications->pluck('serial_no')->toArray();
            $applications = Application::whereIn('serial_no', $serialNos)->get()->keyBy('serial_no');
            $idsToDelete = [];

            foreach ($importantApplications as $item) {
                $serial_no = $item->serial_no;
                if (isset($applications[$serial_no]) && isset($item->br_code)) {
                    $applications[$serial_no]->update([
                        'is_team_f' => 1,
                        'br_code' => $item->br_code,
                    ]);
                    $idsToDelete[] = $item->id;
                }
            }

            if (!empty($idsToDelete)) {
                TeamFData::whereIn('id', $idsToDelete)->delete();
            }

            Alert::success('Team F data added successfully!');
        } catch (\Exception $e) {
            Alert::error('Something went wrong!, Please try again.');
        }

        return back();
    }

    public function allDelete()
    {
        try {
            TeamFData::truncate();
            Alert::success('Team F data deleted successfully!');
        } catch (\Exception $e) {
            Alert::error('Something went wrong!, Please try again.');
        }

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($writtenMarkId)
    {
        try {
            TeamFData::findOrFail($writtenMarkId)->delete();
            Alert::success('Data deleted successfully!');
        } catch (\Exception $e) {
            Alert::error('Something went wrong!, Please try again.');
        }

        return back();
    }
}
