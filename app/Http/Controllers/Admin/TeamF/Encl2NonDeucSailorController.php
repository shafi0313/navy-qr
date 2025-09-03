<?php

namespace App\Http\Controllers\Admin\TeamF;

use PDF;
use App\Exports\Encl2Export;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;
use App\Traits\EnclTrait;
use Maatwebsite\Excel\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class Encl2NonDeucSailorController extends Controller
{
    use ApplicationTrait, EnclTrait;

    public function report($type = null)
    {

        // return view('admin.team-f.encl2-non-deuc-sailor.report', compact('applications'));
        // $pdf = PDF::loadView('admin.team-f.encl2-non-deuc-sailor.pdf', compact('applications'));
        // return $pdf->stream('Encl2.pdf');
        $applications = $this->encl2();

        if ($applications->isEmpty()) {
            Alert::info('No data found');
            return back();
        }

        if ($type && $type == 'pdf') {
            $pdf = PDF::loadView('admin.team-f.encl2-non-deuc-sailor.pdf', compact('applications'));
            return $pdf->stream('Encl2.pdf');
        }
        return view('admin.team-f.encl2-non-deuc-sailor.report', compact('applications'));
    }

    public function exportExcel(Excel $excel)
    {
        return $excel->download(new Encl2Export(), 'Encl2.xlsx');
    }
}
