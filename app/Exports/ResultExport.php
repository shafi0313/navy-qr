<?php

namespace App\Exports;

use App\Models\Application;
use App\Traits\ApplicationTrait;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ResultExport implements FromView
{
    use ApplicationTrait;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $roleId = user()->role_id;
        $query = Application::whereHas('examMark', function ($query) {
            $query->where('dup_test', '=', 'no');
        })->leftJoin('users', 'applications.user_id', '=', 'users.id')
            ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
            ->select(
                array_merge(
                    $this->userColumns(),
                    $this->applicationColumnsForResult(),
                    $this->examColumns(),
                    $this->sscResultColumns(),
                    ['applications.team'],
                    ['applications.is_important']
                )
            )
            ->selectRaw(
                $this->examSumColumns()
            )
            ->where('exam_marks.viva', '>=', 5)
            ->where('is_final_pass', 1)
            ->orderBy('is_medical_pass', 'desc')
            ->orderBy('is_final_pass', 'desc')
            ->orderBy('total_marks', 'desc')
            ->orderBy('total_viva', 'desc');

        if ($roleId != 1) {
            $query->where('users.team', user()->team);
        }

        $applications = $query->get();

        return view('admin.result.export', ['applications' => $applications]);
    }
}
