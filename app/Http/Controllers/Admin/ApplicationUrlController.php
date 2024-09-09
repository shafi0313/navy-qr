<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ApplicationUrl;
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
        // $query = ApplicationUrl::query();
        // return$query->with([
        //     'application:id,application_url_id,post,batch,roll,name',
        //     'application.examMark'
        // ])->whereHas('application.examMark', function ($query) {
        //     $query->where('bangla', '>', 8)
        //           ->where('english', '>', 8)
        //           ->where('math', '>', 8)
        //           ->where('science', '>', 8)
        //           ->where('general_knowledge', '>', 8);
        // })
        // ->select('id', 'url', 'is_written_pass', 'is_final_pass')
        // ->get();
        // return $query = ApplicationUrl::with('application')->get();
        if ($request->ajax()) {
            $roleId = user()->role_id;
            $query = ApplicationUrl::query();

            switch ($roleId) {
                case 1: // Admin
                    $query->with([
                        'application:id,application_url_id,post,batch,roll,name'
                    ]);
                    break;
                case 2: // Normal User
                    $query->select('url');
                    break;
                case 3: // Primary Medical
                    $query->with([
                        'application:id,application_url_id,post,batch,roll,name'
                    ])->select('id', 'url', 'is_medical_pass');
                    break;
                case 4: // Written
                    $query->with([
                        'application:id,application_url_id,post,batch,roll,name'
                    ])->select('id', 'url', 'is_medical_pass', 'is_written_pass')
                        ->where('is_medical_pass', 1);
                    break;
                case 5: // Final Medical
                    $query->with([
                        'application:id,application_url_id,post,batch,roll,name',
                        'application.examMark'
                    ])->whereHas('application.examMark', function ($query) {
                        $query->where('bangla', '>', 8)
                            ->where('english', '>', 8)
                            ->where('math', '>', 8)
                            ->where('science', '>', 8)
                            ->where('general_knowledge', '>', 8);
                    })
                        ->select('id', 'url', 'is_written_pass', 'is_final_pass');
                    break;
                case 6: // Viva / Final Selection
                    $query->with([
                        'application:id,application_url_id,post,batch,roll,name'
                    ])->where('is_final_pass', 1);
                    break;
            }

            $applications = $query;

            return DataTables::eloquent($applications)
                ->addIndexColumn()
                ->addColumn('url', function ($row) {
                    return "<a href='$row->url' target='_blank'>Form</a>";
                })
                ->addColumn('medical', function ($row) {
                    return result($row->is_medical_pass);
                })
                ->addColumn('written', function ($row) {
                    return result($row->is_written_pass);
                })
                ->addColumn('final', function ($row) {
                    return result($row->is_final_pass);
                })
                ->addColumn('viva', function ($row) {
                    return result($row->is_viva_pass);
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
    public function create()
    {
        //
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
