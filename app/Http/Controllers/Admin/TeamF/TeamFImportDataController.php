<?php

namespace App\Http\Controllers\Admin\TeamF;

use App\Models\TeamFData;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Imports\TeamFDataImport;
use App\Http\Controllers\Controller;
use App\Models\ImportantApplication;
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
    public function store(Request $request)
    {
        try {
            $importantApplications = TeamFData::all();

            foreach ($importantApplications as $importantApplication) {

                $application = Application::where('serial_no', $importantApplication->serial_no)->first();
                $application->update(['is_team_f' => 1]);
                TeamFData::findOrFail($importantApplication->id)->delete();
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
