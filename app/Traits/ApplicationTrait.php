<?php

namespace App\Traits;

trait ApplicationTrait
{
    protected function userColumns()
    {
        return [
            'users.id as user_id',
            'users.team as user_team',
        ];
    }

    protected function applicationColumns()
    {
        return [
            'applications.id',
            'applications.team',
            'applications.candidate_designation',
            'applications.exam_date',
            'applications.serial_no',
            'applications.eligible_district',
            'applications.name',
            'applications.is_medical_pass',
            'applications.is_final_pass',
            'applications.height',
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
            'applications.height',
            'applications.photo',
            'applications.p_m_remark',
            'applications.f_m_remark',
            'applications.ssc_group',
            'applications.remark',
        ];
    }

    protected function sscResultColumns()
    {
        return [
            'applications.ssc_edu_board',
            'applications.ssc_gpa',
            'applications.ssc_bangla',
            'applications.ssc_english',
            'applications.ssc_math',
            'applications.ssc_physics',
            'applications.ssc_biology',
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
            'exam_marks.viva_remark',
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

    protected function primaryMedical($row)
    {
        return result($row->is_medical_pass, $row->p_m_remark);
    }

    protected function writtenMark($row)
    {
        // Subjects with display labels
        $subjects = [
            'bangla' => 'Bangla',
            'english' => 'English',
            'math' => 'Math',
            'science' => 'Science',
            'general_knowledge' => 'GK',
        ];

        // Generate HTML only for subjects that have a value
        $marks = collect($subjects)
            ->filter(fn ($label, $subject) => ! empty($row->$subject))
            ->map(fn ($label, $subject) => "<div>{$label}: {$row->$subject}</div>")
            ->implode('');

        return $marks ?: '';

        // return '<span>'
        //         .'Bangla: '.$row->bangla.'<br>'
        //         .'English: '.$row->english.'<br>'
        //         .'Math: '.$row->math.'<br>'
        //         .'Science: '.$row->science.'<br>'
        //         .'GK: '.$row->general_knowledge
        //     .'</span>';

    }

    protected function written($row)
    {
        // If there are no exam marks at all, treat as Pending
        $subjects = ['bangla', 'english', 'math', 'science', 'general_knowledge'];
        $hasAnyMark = false;
        foreach ($subjects as $s) {
            if (! is_null($row->$s)) {
                $hasAnyMark = true;
                break;
            }
        }

        if (! $hasAnyMark) {
            return '<span class="badge bg-warning">Pending</span>';
        }

        $failCount = 0;

        $bangla = $row->bangla ?? 0;
        $english = $row->english ?? 0;
        $math = $row->math ?? 0;
        $science = $row->science ?? 0;
        $gk = $row->general_knowledge ?? 0;

        if ($bangla < 8) {
            $failCount++;
        }
        if ($english < 8) {
            $failCount++;
        }
        if ($math < 8) {
            $failCount++;
        }
        if ($science < 8) {
            $failCount++;
        }
        if ($gk < 8) {
            $failCount++;
        }

        $totalMarks = $row->total_marks ?? ($bangla + $english + $math + $science + $gk);

        if ($failCount == 0) {
            return '<span class="badge bg-success">Pass</span>'.' ('.$totalMarks.')';
        } elseif ($failCount > 0) {
            return '<span class="badge bg-danger">Failed</span> ('.$failCount.' subject(s) failed)';
        }

        return '';
    }

    protected function finalMedical($row)
    {
        return result($row->is_final_pass, $row->f_m_remark);
    }

    protected function viva($row)
    {
        return $row->total_viva !== null ? $row->total_viva : 'Pending';
    }
}
