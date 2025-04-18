<?php

namespace App\Http\Controllers\Admin\Reports;

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

        // Primary Medical
        $data['pMPending'] = $query->whereNull('is_medical_pass')->get();
        $data['pMUnfit'] = $query->where('is_medical_pass', 0)->get();
        $data['pMFit'] = $query->where('is_medical_pass', 1)->get();
        
        // Written
        $data['wPending'] = $query->with(['examMark', fn($q) => $q->where(function ($query) {
            $query->whereNull('bangla')
                ->orWhereNull('english')
                ->orWhereNull('math')
                ->orWhereNull('science')
                ->orWhereNull('general_knowledge');
        })])->get();
        $data['wFail'] = $query->with(['examMark', fn($q) => $q->where(function ($query) {
            $query->where('bangla', '<', 8)
                ->where('english', '<', 8)
                ->where('math', '<', 8)
                ->where('science', '<', 8)
                ->where('general_knowledge', '<', 8);
        })])->get();
        $data['wPass'] = $query->with(['examMark', fn($q) => $q->where(function ($query) {
            $query->where('bangla', '>=', 8)
                ->where('english', '>=', 8)
                ->where('math', '>=', 8)
                ->where('science', '>=', 8)
                ->where('general_knowledge', '>=', 8);
        })])->get();

        return view('admin.report.daily-state.report', $data);
    }
}
