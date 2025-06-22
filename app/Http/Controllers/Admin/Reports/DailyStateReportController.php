<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Exports\DailyStateExport;
use App\Http\Controllers\Controller;
use App\Traits\ApplicationTrait;
use App\Traits\DailyStateReportTrait;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class DailyStateReportController extends Controller
{
    use ApplicationTrait, DailyStateReportTrait;

    public function select()
    {
        return view('admin.report.daily-state.select');
    }

    public function report(Request $request)
    {
        // return 'ok';
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $team = user()->role_id == 1 ? $request->team : user()->team;

        $data = $this->getStateReport($startDate, $endDate, $team);

        return view('admin.report.daily-state.report', $data);
    }

    public function exportExcel($startDate, $endDate, $team, Excel $excel)
    {
        return $excel->download(new DailyStateExport($startDate, $endDate, $team), 'daily_state_report.xlsx');
    }
}
