<?php

namespace App\Http\Controllers\Admin\TeamF;

use PDF;
use Alert;
use App\Traits\EnclTrait;
use App\Exports\Encl1Export;
use Maatwebsite\Excel\Excel;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;

class Encl1DeucSailorController extends Controller
{
    use ApplicationTrait, EnclTrait;

    public function report($type = null)
    {
        $applications = $this->encl1();

        if ($applications->isEmpty()) {
            Alert::info('No data found');
            return back();
        }

        if ($type && $type == 'pdf') {
            $pdf = PDF::loadView('admin.team-f.encl1-deuc-sailor.pdf', compact('applications'));
            return $pdf->stream('Encl1.pdf');
        }
        return view('admin.team-f.encl1-deuc-sailor.report', compact('applications'));
    }

    public function exportExcel(Excel $excel)
    {
        return $excel->download(new Encl1Export(), 'Encl1.xlsx');
    }
}
