<?php

namespace App\Http\Controllers\Admin\Reports;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Controllers\Controller;

class DailyStateReportController extends Controller
{

    use ApplicationTrait;

    public function select()
    {
        return view('admin.report.daily-state.select');
    }

    public function report(Request $request)
    {
        return 'ok';
        $data['startDate'] = $request->start_date;
        $data['endDate'] = $request->end_date;
        if (user()->role_id == 1) {
            $data['team'] = $request->team;
        } else {
            $data['team'] = user()->team;
        }

        $data['designationsQuery'] = Application::selectRaw('candidate_designation')
            ->groupBy('candidate_designation')->get();

        $baseQuery = Application::selectRaw('candidate_designation, COUNT(applications.id) as total')
            ->whereBetween('exam_date', [$request->start_date, $request->end_date]);
        if (user()->role_id == 1) {
            if ($request->team != 'all') {
                $baseQuery->leftJoin('users', 'applications.user_id', '=', 'users.id')
                    ->where('team', $request->team);
            }
        } else {
            $baseQuery->leftJoin('users', 'applications.user_id', '=', 'users.id')
                ->where('team', user()->team);
        }
        $baseQuery->groupBy('candidate_designation');

        $data['applicants'] = (clone $baseQuery)->get();
        $data['attendants'] = (clone $baseQuery)->whereNotNull('scanned_at')->get();

        // Primary Medical
        $data['pMPending'] = (clone $baseQuery)->whereNull('is_medical_pass')->get();
        $data['pMUnfit'] = (clone $baseQuery)->where('is_medical_pass', 0)->get();
        $data['pMFit'] = (clone $baseQuery)->where('is_medical_pass', 1)->get();

        // Written
        $wQuery = Application::leftJoin('users', 'applications.user_id', '=', 'users.id')
            ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
            ->whereBetween('exam_date', [$request->start_date, $request->end_date]);
        if (user()->role_id == 1) {
            if ($request->team != 'all') {
                $wQuery->where('team', $request->team);
            }
        } else {
            $wQuery->where('team', user()->team);
        }
        $wQuery->where('applications.is_medical_pass', 1);

        $data['wPending'] = (clone $wQuery)
            ->where(function ($query) {
                $query->whereNull('bangla')
                    ->orWhereNull('english')
                    ->orWhereNull('math')
                    ->orWhereNull('science')
                    ->orWhereNull('general_knowledge');
            })
            ->selectRaw('candidate_designation, COUNT(applications.id) as total')
            ->groupBy('candidate_designation')
            ->get();

        $data['wFail'] = (clone $wQuery)
            ->where(function ($query) {
                $query->where('bangla', '<', 8)
                    ->orWhere('english', '<', 8)
                    ->orWhere('math', '<', 8)
                    ->orWhere('science', '<', 8)
                    ->orWhere('general_knowledge', '<', 8);
            })
            ->selectRaw('candidate_designation, COUNT(applications.id) as total')
            ->groupBy('candidate_designation')
            ->get();

        $data['wPass'] = (clone $wQuery)
            ->where(function ($query) {
                $query->where('bangla', '>=', 8)
                    ->where('english', '>=', 8)
                    ->where('math', '>=', 8)
                    ->where('science', '>=', 8)
                    ->where('general_knowledge', '>=', 8);
            })
            ->selectRaw('candidate_designation, COUNT(applications.id) as total')
            ->groupBy('candidate_designation')
            ->get();

        // Final Medical
        $fMBase = (clone $wQuery)
            ->where(function ($query) {
                $query->where('bangla', '>=', 8)
                    ->where('english', '>=', 8)
                    ->where('math', '>=', 8)
                    ->where('science', '>=', 8)
                    ->where('general_knowledge', '>=', 8);
            })
            ->selectRaw('candidate_designation, COUNT(applications.id) as total')
            ->groupBy('candidate_designation');

        $data['fMPending'] = (clone $fMBase)->whereNull('is_final_pass')->get();
        $data['fMUnfit'] = (clone $fMBase)->where('is_final_pass', 0)->get();
        $data['fMFit'] = (clone $fMBase)->where('is_final_pass', 1)->get();

        // Viva
        $vivaBase = (clone $fMBase)->where('is_final_pass', 1);
        $data['vPending'] = (clone $vivaBase)->whereNull('viva')->get();
        $data['vFail'] = (clone $vivaBase)->where('viva', '<', 4)->get();
        $data['vPass'] = (clone $vivaBase)->where('viva', '>=', 5)->get();

        // Dope Test
        $dopeBase = (clone $vivaBase)->where('viva', '>=', 5);
        $data['dPending'] = (clone $dopeBase)->whereNull('dup_test')->get();
        $data['dFail'] = (clone $dopeBase)->where('dup_test', 'yes')->get();
        $data['dPass'] = (clone $dopeBase)->where('dup_test', 'no')->get();

        return view('admin.report.daily-state.report', $data);
    }
}
