<?php

namespace App\Http\Controllers\Admin;

use App\Constants\ExamType;
use Illuminate\Http\Request;
use App\Models\ApplicationUrl;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [];

        if (user()->exam_type == ExamType::OFFICER) {
            $data['todayCount'] = ApplicationUrl::whereDate('scanned_at', today())->count();
            $data['allCount'] = ApplicationUrl::count();
        }

        return view('admin.dashboard', $data);
    }
}
