<?php

namespace App\Http\Controllers\Admin\TeamF;

use Alert;
use App\Exports\Encl1Export;
use App\Http\Controllers\Controller;
use App\Traits\ApplicationTrait;
use App\Traits\EnclTrait;
use Maatwebsite\Excel\Excel;
use PDF;

class Encl1DeucSailorController extends Controller
{
    use ApplicationTrait, EnclTrait;

    public function report($type = null)
    {
        if (! in_array(user()->role_id, [1, 2, 8])) {
            Alert::error('You are not authorized to perform this action');
            return back();
        }
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
        if (! in_array(user()->role_id, [1, 2, 8])) {
            Alert::error('You are not authorized to perform this action');
            return back();
        }
        return $excel->download(new Encl1Export, 'Encl1.xlsx');
    }
}
