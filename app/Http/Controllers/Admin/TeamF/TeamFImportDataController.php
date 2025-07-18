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

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        try {
            $importantApplications = TeamFData::pluck('serial_no', 'id');
            $applications = Application::whereIn('serial_no', $importantApplications)->get()->keyBy('serial_no');

            $idsToDelete = [];

            foreach ($importantApplications as $id => $serial_no) {
                if (isset($applications[$serial_no])) {
                    $applications[$serial_no]->update(['is_team_f' => 1]);
                    $idsToDelete[] = $id;
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
