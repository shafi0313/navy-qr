<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicantCountController extends Controller
{
    public function index()
    {
        // return$applicants = Application::groupBy('eligible_district', 'candidate_designation')
        //     ->selectRaw('count(*) as total, candidate_designation, eligible_district')
        //     ->get();

        $applicants = Application::select('id','eligible_district', 'candidate_designation')
            ->get();

        return view('admin.applicant-count.index', compact('applicants'));
    }
}
