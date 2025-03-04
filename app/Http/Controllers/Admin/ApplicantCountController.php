<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicantCountController extends Controller
{
    public function index()
    {
        $applicants = Application::selectRaw('eligible_district, candidate_designation, COUNT(id) as total')
            ->groupBy('eligible_district', 'candidate_designation')
            ->get();


        return view('admin.applicant-count.index', compact('applicants'));
    }
}
