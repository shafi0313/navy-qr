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
        if (user()->role_id == 1) {
            $writtenMarks = WrittenMark::all();

            $teams = ['A', 'B', 'C'];
            $todayWrittenApplicantCount = collect($teams)->mapWithKeys(function ($team) {
                $count = Application::where('team', $team)
                    ->where('is_medical_pass', 1)
                    ->whereDate('exam_date', now()->toDateString())
                    ->whereDoesntHave('examMark')
                    ->count();

                return [$team => $count];
            });
        } else {
            $writtenMarks = WrittenMark::with(['user:id,team'])
                ->whereHas('user', function ($q) {
                    $q->where('team', user()->team);
                })
                ->get();

            $todayWrittenApplicantCount = [
                user()->team => Application::where('team', user()->team)
                    ->where('is_medical_pass', 1)
                    ->whereDate('exam_date', now()->toDateString())
                    ->whereDoesntHave('examMark')
                    ->count(),
            ];
        }

        return view('admin.written-mark-import.index', compact('writtenMarks', 'todayWrittenApplicantCount'));
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

    /**
     * Store a newly created resource in storage.
     */
    public function check(Request $request)
    {
        $writtenMarkIds = explode(',', $request->written_marks);

        $writtenMarks = WrittenMark::whereIn('id', $writtenMarkIds)->get();

        DB::beginTransaction();

        try {
            foreach ($writtenMarks as $writtenMark) {
                $application = Application::select('id', 'is_medical_pass', 'exam_date')
                    ->where('serial_no', $writtenMark->serial_no)
                    ->first();

                // 1️⃣ No application found
                if (! $application) {
                    $writtenMark->update(['remark' => 'Roll No not in database']);

                    continue;
                }

                // 2️⃣ Candidate failed medical
                if ($application->is_medical_pass != 1) {
                    $writtenMark->update(['remark' => 'Not medically passed']);

                    continue;
                }

                // 3️⃣ Exam date mismatch
                if ($application->exam_date !== now()->toDateString()) {
                    $writtenMark->update(['remark' => 'Candidate of another Exam Date']);

                    continue;
                }

                // 4️⃣ Already has exam mark
                $alreadyExists = ExamMark::where('application_id', $application->id)->exists();

                if ($alreadyExists) {
                    $writtenMark->update(['remark' => 'Already added in database']);

                    continue;
                }

                // ✅ Everything passed (you could add your insert logic here)
                $writtenMark->update(['remark' => null]);
            }

            DB::commit();

            Alert::success('Written marks processed successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            Alert::error('Processing failed: '.$e->getMessage());
        }

        return back();
    }

    public function store(Request $request)
    {
        $remarkCheck = WrittenMark::whereIn('id', explode(',', $request->written_marks))->whereNotNull('remark')->exists();
        if ($remarkCheck) {
            Alert::error('Some data could not be added due to errors. Please check the remarks.');

            return back();
        }

        $writtenMarks = WrittenMark::whereIn('id', explode(',', $request->written_marks))->get();
        DB::beginTransaction();

        try {
            foreach ($writtenMarks as $writtenMark) {
                $application = Application::select('id')
                    ->where('serial_no', $writtenMark->serial_no)
                    ->first();

                // 1️⃣ No application found
                if (! $application) {
                    $writtenMark->update(['remark' => 'Roll No not in database']);

                    continue;
                }

                // 2️⃣ Candidate failed medical
                if ($application->is_medical_pass != 1) {
                    $writtenMark->update(['remark' => 'Not medically passed']);

                    continue;
                }

                // 3️⃣ Exam date mismatch
                if ($application->exam_date !== now()->toDateString()) {
                    $writtenMark->update(['remark' => 'Candidate of another Exam Date']);

                    continue;
                }

                // 4️⃣ Already has exam mark
                $alreadyExists = ExamMark::where('application_id', $application->id)->exists();

                if ($alreadyExists) {
                    $writtenMark->update(['remark' => 'Already added in database']);

                    continue;
                }

                $data = [
                    'bangla' => $writtenMark->bangla,
                    'english' => $writtenMark->english,
                    'math' => $writtenMark->math,
                    'science' => $writtenMark->science,
                    'general_knowledge' => $writtenMark->general_knowledge,
                ];
                $data['application_id'] = $application->id;
                ExamMark::create($data);

                // delete after successful move
                $writtenMark->delete();

            }

            DB::commit();
            Alert::success('Written marks processed successfully!');

            return back();
        } catch (\Exception $e) {
            DB::rollBack();
            Alert::error('Some data could not be added due to errors. Please check the remarks.');

            return back();
            // Alert::error('Processing failed: '.$e->getMessage());
        }

    }

    public function allDelete()
    {
        try {
            WrittenMark::truncate();
            Alert::success('Data cleared successfully!');
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
