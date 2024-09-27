<?php

namespace App\Http\Controllers\Admin;

use App\Models\ExamMark;
use App\Models\Application;
use App\Models\ApplicationUrl;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExamMarkRequest;
use App\Http\Requests\UpdateExamMarkRequest;

class ExamMarkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.exam-mark.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreExamMarkRequest $request)
    {
        $data = $request->validated();
        $check = Application::select('id','serial_no', 'name','is_medical_pass')->whereId($request->id)->first();

        if ($check->is_medical_pass != 1) {
            return response()->json(['message' => 'Please update primary medical first'], 404);
        }

        $data['created_by'] = user()->id;

        try {
            ExamMark::updateOrCreate(['id'=> $request->id],$data);
            // ExamMark::create($data);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ExamMark $examMark)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExamMark $examMark)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExamMarkRequest $request, ExamMark $examMark)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamMark $examMark)
    {
        //
    }
}
