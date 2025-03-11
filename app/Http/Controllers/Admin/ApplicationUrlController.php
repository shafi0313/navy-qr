<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApplicationUrl;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ApplicationUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $applicationUrls = ApplicationUrl::with('user:id,name');

            return DataTables::of($applicationUrls)
                ->addIndexColumn()
                ->addColumn('url', function ($row) {
                    return '<a href="'.$row->url.'" target="_blank">'.$row->url.'</a>';
                })
                ->addColumn('scanned_at', function ($row) {
                    return bdDateTime($row->scanned_at);
                })
                ->rawColumns(['url'])
                ->make(true);
        }

        return view('admin.dashboard');
    }
}
