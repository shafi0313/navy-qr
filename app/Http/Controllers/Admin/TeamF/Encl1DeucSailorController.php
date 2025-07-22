<?php

namespace App\Http\Controllers\Admin\TeamF;

use App\Models\Application;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;
use PDF;

class Encl1DeucSailorController extends Controller
{
    use ApplicationTrait;
    
    public function report()
    {
        $roleId = user()->role_id;
        $query = Application::where('is_team_f', 1)
        // ->where('candidate_designation', 'like', 'Sailor(DEUC%')
        ->leftJoin('users', 'applications.user_id', '=', 'users.id')
            ->select(
                array_merge(
                    $this->userColumns(),
                    [
                        'applications.id',
                        'applications.br_code',
                        'applications.eligible_district',
                        'applications.exam_date',
                        'applications.serial_no',
                        'applications.name',
                        'applications.candidate_designation',
                        'applications.ssc_gpa',
                        'applications.height',
                        'applications.ssc_english',
                        'applications.ssc_math',
                        'applications.ssc_physics',
                        'applications.current_phone',
                        'applications.hsc_dip_group',
                    ]
                )
            );
        // if ($roleId != 1) {
        //     $query->where('team', user()->team);
        // }
        $applications = $query->cursor();
        // return view('admin.team-f.encl1-deuc-sailor.report', compact('applications'));

        $pdf = PDF::loadView('admin.team-f.encl1-deuc-sailor.pdf', compact('applications'));
        return $pdf->stream('Encl1.pdf');

    }
}
