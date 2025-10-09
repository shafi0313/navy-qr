<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\WrittenMarkImport;
use App\Models\Application;
use App\Models\ExamMark;
use App\Models\WrittenMark;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        DB::beginTransaction();

        try {
            Excel::import(new WrittenMarkImport, $request->file('file'));
            DB::commit();
            Alert::success('Marks imported successfully!');
        } catch (Exception $e) {
            DB::rollBack();
            Alert::error('Import failed: '.nl2br($e->getMessage()));
        }

        return back();
    }

    // public function import(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx',
    //     ]);

    //     try {
    //         Excel::import(new WrittenMarkImport, $request->file('file'));
    //         Alert::success('Mark imported successfully!');
    //     } catch (\Exception $e) {
    //         Alert::error('Something went wrong!, Please try again.');
    //     }

    //     return back();
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $writtenMarks = WrittenMark::all();
        try {
            foreach ($writtenMarks as $writtenMark) {
                $application = Application::select('id')->where('serial_no', $writtenMark->serial_no)->first();

                if (! $application) {
                    \Log::warning('Application not found: '.$writtenMark->serial_no);

                    continue;
                }

                $examMark = ExamMark::find($application->id);
                if ($examMark) {

                    $examMark->update([
                        'bangla' => $writtenMark->bangla,
                        'english' => $writtenMark->english,
                        'math' => $writtenMark->math,
                        'science' => $writtenMark->science,
                        'general_knowledge' => $writtenMark->general_knowledge,
                    ]);
                } else {
                    ExamMark::create(
                        [
                            'application_id' => $application->id,
                            'bangla' => $writtenMark->bangla,
                            'english' => $writtenMark->english,
                            'math' => $writtenMark->math,
                            'science' => $writtenMark->science,
                            'general_knowledge' => $writtenMark->general_knowledge,
                        ]
                    );
                }

                $writtenMark->delete();

            }
            Alert::success('Written marks processed successfully!');

            return back();
        } catch (Exception $e) {
            return $e->getMessage();
        }

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
