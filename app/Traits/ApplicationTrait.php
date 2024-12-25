<?php

namespace App\Traits;

trait ApplicationTrait
{
    protected function userColumns()
    {
        return [
            'users.id as user_id',
            'users.team as team',
        ];
    }

    protected function applicationColumns()
    {
        return [
            'applications.id',
            'applications.candidate_designation',
            'applications.exam_date',
            'applications.serial_no',
            'applications.eligible_district',
            'applications.name',
            'applications.is_medical_pass',
            'applications.is_final_pass',
            'applications.photo',
            'applications.p_m_remark',
            'applications.f_m_remark',
            'applications.remark',
        ];
    }

    protected function applicationColumnsForResult()
    {
        return [
            'applications.id',
            'applications.candidate_designation',
            'applications.exam_date',
            'applications.serial_no',
            'applications.eligible_district',
            'applications.name',
            'applications.dob',
            'applications.is_medical_pass',
            'applications.is_final_pass',
            'applications.photo',
            'applications.p_m_remark',
            'applications.f_m_remark',
            'applications.ssc_group',
            'applications.ssc_gpa',
            'applications.remark',
        ];
    }

    protected function examColumns()
    {
        return [
            'exam_marks.bangla',
            'exam_marks.english',
            'exam_marks.math',
            'exam_marks.science',
            'exam_marks.general_knowledge',
        ];
    }

    protected function examSumColumns()
    {
        return
            '(exam_marks.bangla +
                exam_marks.english +
                exam_marks.math +
                exam_marks.science +
                exam_marks.general_knowledge) as total_marks,
                exam_marks.viva as total_viva';
    }

    protected function primaryMedical($roleId, $row)
    {
        if (in_array($roleId, [1, 2, 3, 4, 5, 6])) {
            return result($row->is_medical_pass, $row->p_m_remark);
        } else {
            return '';
        }
    }

    protected function written($roleId, $row)
    {
        if (in_array($roleId, [1, 2, 3, 4, 5]) && ($row->bangla || $row->english || $row->math || $row->science || $row->general_knowledge)) {
            $row->bangla + $row->english + $row->math + $row->science + $row->general_knowledge;
            $failCount = 0;
            // Check each subject mark and count fails
            if ($row->bangla < 8) {
                $failCount++;
            }
            if ($row->english < 8) {
                $failCount++;
            }
            if ($row->math < 8) {
                $failCount++;
            }
            if ($row->science < 8) {
                $failCount++;
            }
            if ($row->general_knowledge < 8) {
                $failCount++;
            }
            // If no subject failed and all marks are >= 8, it's a pass
            if ($failCount == 0) {
                return '<span class="badge bg-success">Pass</span>'.' ('.$row->total_marks.')';
            }
            // If there are any fails, it's a fail
            elseif ($failCount > 0) {
                return '<span class="badge bg-danger">Failed</span> ('.$failCount.' subject(s) failed)';
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    protected function finalMedical($roleId, $row)
    {
        if (in_array($roleId, [1, 2, 3, 4])) {
            return result($row->is_final_pass, $row->f_m_remark);
        } else {
            return '';
        }
    }

    protected function viva($roleId, $row)
    {
        if (in_array($roleId, [1, 2, 3])) {
            return $row->total_viva;
        } else {
            return '';
        }
    }
}
