<?php

namespace App\Http\Controllers\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DailyStateReportController extends Controller
{
    public function select()
    {
        return view('admin.report.daily-state.select');
    }

    public function report()
    {
        // return

        $query = Application::selectRaw('candidate_designation, COUNT(id) as total')
            // ->whereNotNull('scanned_at')
            ->groupBy('candidate_designation');



        $data['applicants'] = $query->get();
        $data['attendants'] = $query->whereNotNull('scanned_at')->get();
        $data['pMUnfit'] = $query->where('is_medical_pass', 0)->get();

        return view('admin.report.daily-state.report', $data);
    }
}
