<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicantCountController extends Controller
{
    public function index()
    {
        return Application::groupBy('candidate_designation', 'eligible_district')
            ->selectRaw('count(*) as total, candidate_designation, eligible_district')
            ->get();
    }
}
