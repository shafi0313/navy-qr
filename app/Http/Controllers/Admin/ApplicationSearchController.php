<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApplicationTrait;
use Yajra\DataTables\Facades\DataTables;

class ApplicationSearchController extends Controller
{
    use ApplicationTrait;
    public function index(Request $request)
    {
        // $application = Application::findOrFail($request->application_id)
        //     ->leftJoin('users', 'applications.user_id', '=', 'users.id')
        //     ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
        //     ->select(
        //         array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
        //     )
        //     ->selectRaw(
        //         $this->examSumColumns()
        //     );
        return view('admin.application-search.index');
    }

    public function show($id)
    {
        $data = Application::whereId($id)->get();

            if ($data) {
                return response()->json(['success' => true, 'data' => $data]);
            } else {
                return response()->json(['success' => false, 'message' => 'Data not found'], 404);
            }
    }

    public function store(Request $request)
    {
        $application = Application::where('id',$request->application_id)->first();
        try {
            $application->update(['user_id' => user()->id]);
            return response()->json(['message' => 'The information has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
        }
    }
}
