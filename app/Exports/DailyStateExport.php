<?php

namespace App\Exports;

use App\Traits\DailyStateReportTrait;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DailyStateExport implements FromView
{
    use DailyStateReportTrait;

    /**
     * @return \Illuminate\Support\Collection
     */
    protected $startDate;

    protected $endDate;

    protected $team;

    public function __construct($startDate, $endDate, $team)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->team = $team;
    }

    public function view(): View
    {
        $data = $this->getStateReport($this->startDate, $this->endDate, $this->team);

        return view('admin.report.daily-state.excel-export', $data);
    }
}
