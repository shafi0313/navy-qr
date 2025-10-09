<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ExamType;
use App\Http\Controllers\Controller;
use App\Models\AppInstruction;
use App\Models\Application;
use App\Models\ApplicationUrl;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];

        if (user()->exam_type == ExamType::OFFICER) {
            if (user()->role_id == 1) {
                $data['counts'] = ApplicationUrl::join('users', 'users.id', '=', 'application_urls.user_id')
                    ->whereNotNull('application_urls.scanned_at')
                    ->selectRaw('users.team, COUNT(*) as count, SUM(CASE WHEN DATE(application_urls.scanned_at) = CURDATE() THEN 1 ELSE 0 END) as today_count')
                    ->groupBy('users.team')
                    ->get();
            } else {
                $data['counts'] = ApplicationUrl::join('users', 'users.id', '=', 'application_urls.user_id')
                    ->where('users.team', user()->team)
                    ->whereNotNull('application_urls.scanned_at')
                    ->selectRaw('users.team, COUNT(*) as count, SUM(CASE WHEN DATE(application_urls.scanned_at) = CURDATE() THEN 1 ELSE 0 END) as today_count')
                    ->groupBy('users.team')
                    ->get();
            }
            $data = $data;
        } else {
            $teams = [
                'A' => team('a'),
                'B' => team('b'),
                'C' => team('c'),
            ];

            $data = [];
            if (user()->role_id == 1) {
                // Role 1: Show all teams
                foreach ($teams as $teamName => $districts) {
                    $data[] = [
                        'team' => $teamName,
                        'stats' => $this->getTeamData($districts),
                    ];
                }
            } else {
                // Other users: Show only their team
                if (user()->team == 'A' && isset($teams['A'])) {
                    $data[] = [
                        'team' => 'A',
                        'stats' => $this->getTeamData($teams['A']),
                    ];
                } elseif (user()->team == 'B' && isset($teams['B'])) {
                    $data[] = [
                        'team' => 'B',
                        'stats' => $this->getTeamData($teams['B']),
                    ];
                } elseif (user()->team == 'C' && isset($teams['C'])) {
                    $data[] = [
                        'team' => 'C',
                        'stats' => $this->getTeamData($teams['C']),
                    ];
                }
            }

            $data = ['data' => $data];
        }
        $menuOrder = implode(',', config('var.menuNameOrder'));
        $data['appInstructions'] = AppInstruction::orderByRaw("FIELD(menu_name, $menuOrder)")->get();

        return view('admin.dashboard', $data);
    }

    public function getTeamData(array $districts)
    {
        $query = Application::query()
            ->selectRaw('
            SUM(CASE WHEN DATE(exam_date) = ? THEN 1 ELSE 0 END) as todayApplicants,
            SUM(CASE WHEN scanned_at IS NOT NULL THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN DATE(scanned_at) = ? THEN 1 ELSE 0 END) as today
        ', [now()->toDateString(), now()->toDateString()])
            ->whereIn('eligible_district', $districts);

        if (user()->role_id == 7) {
            $query->where('user_id', user()->id);
        }

        return $query->first() ?? (object) [
            'todayApplicants' => 0,
            'present' => 0,
            'today' => 0,
        ];
        //     $query = Application::selectRaw('
        //     COUNT(CASE WHEN DATE(exam_date) = CURRENT_DATE THEN 1 END) as todayApplicants,
        //     COUNT(CASE WHEN scanned_at IS NOT NULL THEN 1 END) as present,
        //     COUNT(CASE WHEN DATE(scanned_at) = CURRENT_DATE THEN 1 END) as today
        // ')
        //         ->whereIn('eligible_district', $districts);

        //     if (user()->role_id == 7) {
        //         $query->where('user_id', user()->id);
        //     }

        //     return $query->first();
    }
}
