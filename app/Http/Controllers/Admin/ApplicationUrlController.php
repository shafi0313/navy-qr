<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ApplicationUrl;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreApplicationUrlRequest;
use App\Http\Requests\UpdateApplicationUrlRequest;

class ApplicationUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    //     $query = ApplicationUrl::query();
    //     return $query->with([
    //         'application:id,application_url_id,post,batch,roll,name',
    //         'application.examMark:id,application_id,bangla,english,math,science,general_knowledge,viva'
    //     ])->select('application_urls.id', 'is_medical_pass', 'is_final_pass')
    //         ->leftJoin('applications', 'applications.application_url_id', '=', 'application_urls.id')
    //         ->leftJoin('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
    //         ->selectRaw('
    //     (COALESCE(exam_marks.bangla, 0) +
    //     COALESCE(exam_marks.english, 0) +
    //     COALESCE(exam_marks.math, 0) +
    //     COALESCE(exam_marks.science, 0) +
    //     COALESCE(exam_marks.general_knowledge, 0)) as total_marks
    // ')
    //         ->groupBy('application_urls.id', 'application_urls.is_medical_pass', 'application_urls.is_final_pass', 'exam_marks.bangla', 'exam_marks.english', 'exam_marks.math', 'exam_marks.science', 'exam_marks.general_knowledge')
    //         ->orderBy('total_marks', 'desc')
    //         ->get();


        // return $query->select('url')->get();
        // return $query->with([
        //     'application:id,application_url_id,post,batch,roll,name'
        // ])->get();


        if ($request->ajax()) {
            $roleId = user()->role_id;
            $query = ApplicationUrl::query();

            switch ($roleId) {
                case 1: // Admin
                    $query->with([
                        'application:id,application_url_id,post,batch,roll,name',
                        'application.examMark:id,application_id,bangla,english,math,science,general_knowledge,viva'
                    ])->select('application_urls.id', 'is_medical_pass', 'is_final_pass')
                        ->leftJoin('applications', 'applications.application_url_id', '=', 'application_urls.id')
                        ->leftJoin('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
                        ->selectRaw('
                        (COALESCE(exam_marks.bangla, 0) +
                        COALESCE(exam_marks.english, 0) +
                        COALESCE(exam_marks.math, 0) +
                        COALESCE(exam_marks.science, 0) +
                        COALESCE(exam_marks.general_knowledge, 0)) as total_marks
                    ')->groupBy('application_urls.id', 'application_urls.is_medical_pass', 'application_urls.is_final_pass', 'exam_marks.bangla', 'exam_marks.english', 'exam_marks.math', 'exam_marks.science', 'exam_marks.general_knowledge')
                        ->orderBy('total_marks', 'desc');
                    break;
                case 2: // Normal User
                    $query->with([
                        'application:id,application_url_id,post,batch,roll,name',
                    ])->select('id', 'url');
                    break;
                case 3: // Primary Medical
                    $query->with([
                        'application:id,application_url_id,post,batch,roll,name',
                        'application.examMark:id'
                    ])->select('id', 'url', 'is_medical_pass');
                    break;
                case 4: // Written
                    $query->with([
                        'application:id,application_url_id,post,batch,roll,name',
                        'application.examMark:id,application_id,bangla,english,math,science,general_knowledge,viva'
                    ])->select('application_urls.id', 'is_medical_pass', 'is_final_pass')
                        ->leftJoin('applications', 'applications.application_url_id', '=', 'application_urls.id')
                        ->leftJoin('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
                        ->selectRaw('
                        (COALESCE(exam_marks.bangla, 0) +
                        COALESCE(exam_marks.english, 0) +
                        COALESCE(exam_marks.math, 0) +
                        COALESCE(exam_marks.science, 0) +
                        COALESCE(exam_marks.general_knowledge, 0)) as total_marks
                    ')->groupBy('application_urls.id', 'application_urls.is_medical_pass', 'application_urls.is_final_pass', 'exam_marks.bangla', 'exam_marks.english', 'exam_marks.math', 'exam_marks.science', 'exam_marks.general_knowledge')
                        ->orderBy('total_marks', 'desc');
                    break;
                case 5: // Final Medical
                    $query->with([
                        'application:id,application_url_id,post,batch,roll,name',
                        'application.examMark:id,application_id,bangla,english,math,science,general_knowledge'
                    ])
                        ->whereHas('application.examMark', function ($query) {
                            $query->where('bangla', '>=', 8)
                                ->where('english', '>=', 8)
                                ->where('math', '>=', 8)
                                ->where('science', '>=', 8)
                                ->where('general_knowledge', '>=', 8);
                        })
                        ->select('application_urls.id', 'is_medical_pass', 'is_final_pass')
                        ->leftJoin('applications', 'applications.application_url_id', '=', 'application_urls.id')
                        ->leftJoin('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
                        ->selectRaw('
                        (COALESCE(exam_marks.bangla, 0) +
                        COALESCE(exam_marks.english, 0) +
                        COALESCE(exam_marks.math, 0) +
                        COALESCE(exam_marks.science, 0) +
                        COALESCE(exam_marks.general_knowledge, 0)) as total_marks
                    ')->groupBy('application_urls.id', 'application_urls.is_medical_pass', 'application_urls.is_final_pass', 'exam_marks.bangla', 'exam_marks.english', 'exam_marks.math', 'exam_marks.science', 'exam_marks.general_knowledge')
                        ->orderBy('total_marks', 'desc');
                    break;
                case 6: // Viva / Final Selection
                    $query->with([
                        'application:id,application_url_id,post,batch,roll,name',
                        'application.examMark:id,application_id,bangla,english,math,science,general_knowledge,viva'
                    ])
                        ->whereHas('application.examMark', function ($query) {
                            $query->where('bangla', '>=', 8)
                                ->where('english', '>=', 8)
                                ->where('math', '>=', 8)
                                ->where('science', '>=', 8)
                                ->where('general_knowledge', '>=', 8);
                        })
                        ->leftJoin('applications', 'applications.application_url_id', '=', 'application_urls.id')
                        ->leftJoin('exam_marks', 'exam_marks.application_id', '=', 'applications.id')
                        ->select(
                            'application_urls.id',
                            'is_medical_pass',
                            'is_final_pass',
                            'applications.id as application_id'
                        )
                        ->selectRaw('
                        (COALESCE(exam_marks.bangla, 0) +
                        COALESCE(exam_marks.english, 0) +
                        COALESCE(exam_marks.math, 0) +
                        COALESCE(exam_marks.science, 0) +
                        COALESCE(exam_marks.general_knowledge, 0)) as total_marks,
                        COALESCE(exam_marks.viva, 0) as total_viva
                    ')
                        ->groupBy(
                            'application_urls.id',
                            'applications.id',
                            'is_medical_pass',
                            'is_final_pass',
                            'exam_marks.bangla',
                            'exam_marks.english',
                            'exam_marks.math',
                            'exam_marks.science',
                            'exam_marks.general_knowledge',
                            'exam_marks.viva'
                        )
                        ->orderBy('total_viva', 'desc');

                    break;
            }
            $applications = $query;

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('roll', function ($row) {
                    return $row->application ? $row->application->roll : '';
                })
                ->addColumn('name', function ($row) {
                    return $row->application ? $row->application->name : '';
                })
                ->addColumn('url', function ($row) {
                    return "<a href='$row->url' target='_blank'>Form</a>";
                })
                ->addColumn('medical', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 3, 4, 5, 6])) {
                        return result($row->is_medical_pass);
                    } else {
                        return '';
                    }
                })
                ->addColumn('written', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 4, 5, 6]) && $row->application && $row->application->examMark) {
                        $written = $row->application->examMark;
                        $totalMark = $written->bangla + $written->english + $written->math + $written->science + $written->general_knowledge;
                        if ($written->bangla >= 8 && $written->english >= 8 && $written->math >= 8 && $written->science >= 8 && $written->general_knowledge >= 8) {
                            return '<span class="badge bg-success">Pass</span>' . ' ' . ($row->total_marks ? $row->total_marks : '');
                        } elseif ($totalMark > 2 && ($written->bangla <= 7 || $written->english <= 7 || $written->math <= 7 || $written->science <= 7 || $written->general_knowledge <= 7)) {
                            return '<span class="badge bg-danger">Fail</span>';
                        } else {
                            return '<span class="badge bg-warning">Pending</span>';
                        }
                    } else {
                        return '';
                    }
                })
                ->addColumn('final', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 5, 6])) {
                        return result($row->is_final_pass);
                    } else {
                        return '';
                    }
                })
                ->addColumn('viva', function ($row) use ($roleId) {
                    if (in_array($roleId, [1, 5, 6])) {
                        return $row->application->examMark->viva;
                    } else {
                        return '';
                    }
                })
                // ->addColumn('action', function ($row) {
                //     $btn = '';
                //     if (userCan('admin-edit')) {
                //         $btn .= view('button', ['type' => 'ajax-edit', 'route' => route('admin.admins.edit', $row->id), 'row' => $row]);
                //     }
                //     if (userCan('admin-delete')) {
                //         $btn .= view('button', ['type' => 'ajax-delete', 'route' => route('admin.admins.destroy', $row->id), 'row' => $row, 'src' => 'dt']);
                //     }
                //     return $btn;
                // })
                // ->filter(function ($query) use ($request) {
                //     if ($request->has('gender') && $request->gender != '') {
                //         $query->where('gender', $request->gender);
                //     }
                //     if ($search = $request->get('search')['value']) {
                //         $query->search($search);
                //     }
                // })
                ->rawColumns(['url', 'medical', 'written', 'final', 'viva', 'action'])
                ->make(true);
        }

        return view('admin.application-url.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function medicalPassStatus(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required|exists:application_urls,id',
            'is_medical_pass' => 'required|boolean',
        ]);

        $applicationUrl = ApplicationUrl::with([
            'application:id,application_url_id,post,batch,roll,name'
        ])->select('id', 'url', 'is_medical_pass')->findOrFail($request->id);

        try {
            $applicationUrl->update(['is_medical_pass' => $request->is_medical_pass]);
            return response()->json(['message' => 'The status has been updated'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApplicationUrlRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ApplicationUrl $applicationUrl)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApplicationUrl $applicationUrl)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApplicationUrlRequest $request, ApplicationUrl $applicationUrl)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApplicationUrl $applicationUrl)
    {
        //
    }
}
