<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sms;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SmsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $smss = Sms::with(['user:id,name'])->latest();

            return DataTables::of($smss)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return bdDateTime($row->created_at);
                })
                ->rawColumns([])
                ->make(true);
        }

        $thisMonth = Sms::whereMonth('created_at', now()->month)->count();
        $allTime = Sms::count();

        return view('admin.sms.index', compact('thisMonth', 'allTime'));
    }
}
