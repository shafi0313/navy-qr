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
