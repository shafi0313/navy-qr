<?php

namespace App\Http\Controllers\Admin\TeamF;

use App\Exports\Encl2Export;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Traits\ApplicationTrait;
use App\Traits\EnclTrait;
use Illuminate\Http\Request;
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

    // public function enclEditModal(Request $request)
    // {
    //     if (! in_array(user()->role_id, [1, 2, 8])) {
    //         return response()->json(['message' => 'You are not authorized to perform this action'], 403);
    //     }

    //     if ($request->ajax()) {
    //         $applicant = Application::select('id', 'name', 'serial_no', 'br_code', 'encl_remark')->find($request->id);
    //         $modal = view('admin.team-f.encl2-non-deuc-sailor.modal', ['applicant' => $applicant])->render();

    //         return response()->json(['modal' => $modal], 200);
    //     }

    //     return abort(403, 'Unauthorized action.');
    // }

    // public function updateEnclRemark(Request $request)
    // {
    //     if (! in_array(user()->role_id, [1, 2, 8])) {
    //         return response()->json(['message' => 'You are not authorized to perform this action'], 403);
    //     }

    //     $request->validate([
    //         'application_id' => 'required|exists:applications,id',
    //         'encl_remark' => 'nullable|string|max:255',
    //     ]);

    //     try {
    //         $application = Application::find($request->application_id);
    //         $application->encl_remark = $request->encl_remark;
    //         $application->save();
    //         Alert::success('Success', 'The information has been updated');
    //         // return response()->json(['message' => 'The information has been updated'], 200);
    //     } catch (\Exception $e) {
    //         Alert::error('Error', 'Oops something went wrong, Please try again');
    //         // return response()->json(['message' => 'Oops something went wrong, Please try again'], 500);
    //     }

    //     return back();
    // }
}
