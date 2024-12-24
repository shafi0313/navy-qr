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
        if ($request->ajax()) {
            $applicationUrls = ApplicationUrl::query();

            return DataTables::of($applicationUrls)
                ->addIndexColumn()
                ->addColumn('url', function ($row) {
                    return '<a href="' . $row->url . '" target="_blank">' . $row->url . '</a>';
                })
                ->rawColumns(['url'])
                ->make(true);
        }
        return view('admin.application-url.index');
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
