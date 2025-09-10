<?php

namespace App\Traits;

trait ApplicationValidationTrait
{
    protected function examDateCheck($application)
    {
        if ($application->exam_date !== now()->toDateString()) {
            $exam_date_check = 'Exam date mismatch.';
        } else {
            $exam_date_check = true;
        }
        return $exam_date_check;
    }

    protected function venueCheck($application)
    {
        $teams = [
            'A' => team('a'),
            'B' => team('b'),
            'C' => team('c'),
        ];

        $applicantTeam = null;
        foreach ($teams as $teamName => $districts) {
            if (in_array(strtolower($application->district), $districts)) {
                $applicantTeam = $teamName;
                break;
            }
        }

        if (user()->role_id !== 1 && user()->team !== $applicantTeam) {
            $venue_check = 'Venue mismatch.';
        } else {
            $venue_check = true;
        }

        return $venue_check;
    }
}