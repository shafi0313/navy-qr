<?php

namespace App\Http\Controllers\Admin\TeamF;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;

class Encl1DeucSailorController extends Controller
{
    use ApplicationTrait;
    
    public function report()
    {
        $roleId = user()->role_id;
        $query = Application::where('is_team_f', 1);
        $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
            ->select(
                array_merge(
                    $this->userColumns(),
                    [
                        'applications.id',
                        'applications.eligible_district',
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
        if ($roleId != 1) {
            $query->where('team', user()->team);
        }
        $applications = $query->cursor();
        return view('admin.team-f.encl1-deuc-sailor.report', compact('applications'));
    }
}
