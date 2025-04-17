<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ExamType;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationUrl;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
        } else {
            // For Sailor
            if (user()->role_id == 1) {
                $data['counts'] = Application::join('users', 'users.id', '=', 'applications.user_id')
                    ->whereNotNull('applications.scanned_at')
                    ->selectRaw('users.team, COUNT(*) as count, SUM(CASE WHEN DATE(applications.scanned_at) = CURDATE() THEN 1 ELSE 0 END) as today_count')
                    ->groupBy('users.team')
                    ->get();
            } elseif(in_array(user()->role_id, [2, 3, 4, 5])) {
                $data['counts'] = Application::join('users', 'users.id', '=', 'applications.user_id')
                    ->where('users.id', user()->team)
                    ->whereNotNull('applications.scanned_at')
                    ->selectRaw('users.team, COUNT(*) as count, SUM(CASE WHEN DATE(applications.scanned_at) = CURDATE() THEN 1 ELSE 0 END) as today_count')
                    ->groupBy('users.team')
                    ->get();
            }else{
                $data['counts'] = Application::join('users', 'users.id', '=', 'applications.user_id')
                    ->where('users.id', user()->id)
                    ->whereNotNull('applications.scanned_at')
                    ->selectRaw('users.id, COUNT(*) as count, SUM(CASE WHEN DATE(applications.scanned_at) = CURDATE() THEN 1 ELSE 0 END) as today_count')
                    ->groupBy('users.id')
                    ->get();
            }
        }

        return view('admin.dashboard', $data);
    }
}
