<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ImportantApplicationImport;
use App\Models\Application;
use App\Models\ImportantApplication;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class ApplicationImportantController extends Controller
{
    public function index()
    {
        $writtenMarks = ImportantApplication::paginate(30);

        return view('admin.important-application-import.index', compact('writtenMarks'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        try {
            Excel::import(new ImportantApplicationImport, $request->file('file'));
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

        $importantApplications = ImportantApplication::all();

        foreach ($importantApplications as $importantApplication) {

            $application = Application::where('serial_no', $importantApplication->serial_no)->first();
            $application->update(['is_important' => 1]);
            ImportantApplication::findOrFail($importantApplication->id)->delete();
        }

        try {
            Alert::success('Important Application added successfully!');
        } catch (\Exception $e) {
            Alert::error('Something went wrong!, Please try again.');
        }

        return back();
    }

    public function allDelete()
    {
        try {
            ImportantApplication::truncate();
            Alert::success('Important Application deleted successfully!');
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
            ImportantApplication::findOrFail($writtenMarkId)->delete();
            Alert::success('All question deleted successfully!');
        } catch (\Exception $e) {
            Alert::error('Something went wrong!, Please try again.');
        }

        return back();
    }
}
