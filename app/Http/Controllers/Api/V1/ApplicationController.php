<?php

namespace App\Http\Controllers\Api\V1;

use App\Constants\ExamType;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Models\ApplicationUrl;
use App\Traits\ApplicationTrait;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ApplicationResource;
use App\Http\Controllers\Api\V1\BaseController as BaseController;

class ApplicationController extends BaseController
{
    use ApplicationTrait;

    public function index()
    {
        $roleId = user()->role_id;
        $query = Application::query();

        if ($roleId == 1) {
            $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                ->select(
                    array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                )
                ->selectRaw(
                    $this->examSumColumns()
                );
        } else {
            $query->leftJoin('users', 'applications.user_id', '=', 'users.id')
                ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                ->select(
                    array_merge($this->userColumns(), $this->applicationColumns(), $this->examColumns())
                )
                ->selectRaw(
                    $this->examSumColumns()
                )->where('team', user()->team);
        }
        $applications = $query->paginate(20);

        return $this->sendResponse(ApplicationResource::collection($applications), 'Applicants list.');
    }

    public function show($serialNo)
    {
        $application = Application::with('examMark')->where('serial_no', $serialNo)->first();

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

        // 👇 concat (merge) into model object without saving to DB
        $application->team_by_district = $applicantTeam;

        if ($application) {
            if (! is_null($application->scanned_at)) {
                return $this->sendResponse(new ApplicationResource($application), 'Already Scanned.');
            }

            // $application->update(['scanned_at' => now()]);
            // $application->update(['user_id' => user()->id]);

            return $this->sendResponse(new ApplicationResource($application), 'Applicant info.');
        } else {
            return $this->sendError('No Data Found.', [], 404);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:applications,id',
            'status' => 'required|in:yes,no',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors(), 422);
        }

        $validated = $validator->validated();
        $application = Application::findOrFail($validated['id']);

        // Update based on status
        if ($validated['status'] === 'yes') {
            $application->update([
                'user_id' => user()->id,
                'scanned_at' => now(),
            ]);

            return $this->sendResponse($validated, 'Application accepted.');
        } else {
            $application->update([
                'user_id' => null,
                'scanned_at' => null,
            ]);

            return $this->sendResponse($validated, 'Application rejected.');
        }
    }


    public function count()
    {
        if (user()->exam_type == ExamType::SAILOR) {
            $data['allApplicationsByUser'] = Application::where('user_id', user()->id)->count();
            $data['todayApplicationsByUser'] = Application::where('user_id', user()->id)->whereDate('scanned_at', now())->count();
        } elseif (user()->exam_type == ExamType::OFFICER) {
            $data['allApplicationsByUser'] = ApplicationUrl::where('user_id', user()->id)->count();
            $data['todayApplicationsByUser'] = ApplicationUrl::where('user_id', user()->id)->whereDate('scanned_at', now())->count();
        }

        return $this->sendResponse($data, 'Applicants count.');
    }

    public function preMedicalCount()
    {
        // $data['allFitByUser'] = Application::where('user_id', user()->id)->where('is_medical_pass', 1)->count();
        // $data['allUnfitByUser'] = Application::where('user_id', user()->id)->where('is_medical_pass', 0)->count();
        // $data['todayFitByUser'] = Application::where('user_id', user()->id)->where('is_medical_pass', 1)->whereDate('scanned_at', now())->count();
        // $data['todayUnfitByUser'] = Application::where('user_id', user()->id)->where('is_medical_pass', 0)->whereDate('scanned_at', now())->count();


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

        // $data;

        return $this->sendResponse($data, 'Applicants Pre Medical count.');
    }

    public function getTeamData(array $districts)
    {
        // COUNT(*) as total,
        $query = Application::selectRaw('
            COUNT(CASE WHEN is_medical_pass = 1 THEN 1 END) as fit,
            COUNT(CASE WHEN is_medical_pass = 0 THEN 1 END) as unfit
        ')
            ->whereIn('eligible_district', $districts);

        if (user()->role_id == 7) {
            $query->where('user_id', user()->id);
        }

        return $query->first();
    }
}
