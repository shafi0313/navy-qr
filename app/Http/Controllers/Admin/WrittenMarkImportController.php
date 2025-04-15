<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\WrittenMarkImport;
use App\Models\Application;
use App\Models\ExamMark;
use App\Models\WrittenMark;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class WrittenMarkImportController extends Controller
{
    public function index()
    {
        $writtenMarks = WrittenMark::all();
        // $writtenMarks = WrittenMark::paginate(30);

        return view('admin.written-mark-import.index', compact('writtenMarks'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        try {
            Excel::import(new WrittenMarkImport, $request->file('file'));
            Alert::success('Mark imported successfully!');
        } catch (\Exception $e) {
            Alert::error('Something went wrong!, Please try again.');
        }

        return back();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $writtenMarks = WrittenMark::all();

        foreach ($writtenMarks as $writtenMark) {
            $application = Application::where('serial_no', $writtenMark->serial_no)->first();

            // If the question already exists, skip to the next iteration
            if (! $application) {
                continue;
            }

            $examMark = ExamMark::where('application_id', $application->id)->first();
            $markData = [
                'application_id' => $application->id,
                'bangla' => $writtenMark->bangla,
                'english' => $writtenMark->english,
                'math' => $writtenMark->math,
                'science' => $writtenMark->science,
                'general_knowledge' => $writtenMark->general_knowledge,
            ];
            if ($examMark) {
                $examMark->update($markData);
            } else {
                $examMark = ExamMark::create($markData);
            }

            WrittenMark::findOrFail($writtenMark->id)->delete();
        }

        try {
            Alert::success('Written marks added successfully!');
        } catch (\Exception $e) {
            Alert::error('Something went wrong!, Please try again.');
        }

        return back();
    }

    public function allDelete()
    {
        try {
            WrittenMark::truncate();
            Alert::success('Written marks deleted successfully!');
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
            WrittenMark::findOrFail($writtenMarkId)->delete();
            Alert::success('All question deleted successfully!');
        } catch (\Exception $e) {
            Alert::error('Something went wrong!, Please try again.');
        }

        return back();
    }
}
