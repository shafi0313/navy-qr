<?php

namespace App\Http\Controllers\Admin\TeamF;

use App\Exports\Encl2Export;
use App\Http\Controllers\Controller;
use App\Traits\ApplicationTrait;
use App\Traits\EnclTrait;
use Maatwebsite\Excel\Excel;
use PDF;
use RealRashid\SweetAlert\Facades\Alert;

class Encl2NonDeucSailorController extends Controller
{
    use ApplicationTrait, EnclTrait;

    public function report($type = null)
    {
        if (! in_array(user()->role_id, [1, 2, 8])) {
            Alert::error('You are not authorized to perform this action');

            return back();
        }

        $applications = $this->encl2();

        // if ($applications->isEmpty()) {
        //     Alert::info('No data found');
        //     return back();
        // }

        // if ($type && $type == 'pdf') {
        //     $pdf = PDF::loadView('admin.team-f.encl2-non-deuc-sailor.pdf', compact('applications'));
        //     return $pdf->stream('Encl2.pdf');
        // }
        return view('admin.team-f.encl2-non-deuc-sailor.report', compact('applications'));
    }

    public function exportExcel(Excel $excel)
    {
        if (! in_array(user()->role_id, [1, 2, 8])) {
            Alert::error('You are not authorized to perform this action');

            return back();
        }

        return $excel->download(new Encl2Export, 'Encl2.xlsx');
    }
}
