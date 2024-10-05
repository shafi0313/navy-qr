<?php

namespace App\Traits;

trait ApplicationTrait
{
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
                exam_marks.viva as total_viva'
        ;
    }
}
