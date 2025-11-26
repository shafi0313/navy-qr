<?php

namespace App\Traits;

use App\Models\Application;

trait EnclTrait
{
    use ApplicationTrait;

    protected function encl1()
    {
        // $roleId = user()->role_id;
        return $query = Application::where('is_team_f', 1)
            // ->where('candidate_designation', 'like', 'Sailor(DEUC%')
            ->where('br_code', 1)
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
                        'applications.local_no',
                        'applications.doc_submitted',
                        'applications.doc_submitted_to_bns',
                    ]
                )
            )->cursor();
        // if ($roleId != 1) {
        //     $query->where('users.team', user()->team);
        // }
        // $applications = $query->cursor();
    }

    protected function encl2()
    {
        return $query = Application::where('is_team_f', 1)
            // ->whereNot('candidate_designation', 'like', 'Sailor(DEUC%')
            ->whereIn('br_code', [2, 3, 4, 5, 6, 7, 8, 9, 10])
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
                        'applications.local_no',
                        'applications.doc_submitted',
                        'applications.doc_submitted_to_bns',
                    ]
                )
            )->cursor();

        // if ($roleId != 1) {
        //     $query->where('users.team', user()->team);
        // }
        // $applications = $query->cursor();
    }
}
