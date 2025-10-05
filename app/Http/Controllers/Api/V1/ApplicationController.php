<?php

namespace App\Http\Controllers\Api\V1;

use App\Constants\ExamType;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Models\ApplicationUrl;
use App\Traits\ApplicationTrait;
use App\Traits\ApplicationValidationTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends BaseController
{
    use ApplicationTrait, ApplicationValidationTrait;

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

        // Check exam date & venue
        $application->exam_date_check = $this->examDateCheck($application);
        $application->venue_check = $this->venueCheck($application);

        if ($application) {
            if (! is_null($application->scanned_at)) {
                return $this->sendResponse(new ApplicationResource($application), 'Already Scanned.');
            }

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
        try {
            DB::beginTransaction();
            $application = Application::findOrFail($validated['id']);

            // Check exam date & venue
            if ($this->examDateCheck($application) !== true) {
                return $this->sendError('Exam date mismatch.', [], 403);
            }
            if ($this->venueCheck($application) !== true) {
                return $this->sendError('Venue mismatch.', [], 403);
            }

            // Update based on status
            if (user()->role_id == 7) {
                $updateData = $validated['status'] === 'yes'
                    ? ['user_id' => user()->id, 'scanned_at' => now()]
                    : ['user_id' => null, 'scanned_at' => null];

                $application->update($updateData);

                $message = $validated['status'] === 'yes'
                    ? 'Application accepted.'
                    : 'Application rejected.';
                DB::commit();

                return $this->sendResponse($validated, $message);
            }

            return $this->sendError($validated, [], 200);
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->sendError('Something went wrong.', [$e->getMessage()], 500);
        }
    }

    // public function show($serialNo)
    // {
    //     try {
    //         $application = Application::with('examMark')->where('serial_no', $serialNo)->first();

    //         // Check exam date & venue
    //         $application->exam_date_check = $this->examDateCheck($application);
    //         $application->venue_check = $this->venueCheck($application);

    //         if ($application) {
    //             if (! is_null($application->scanned_at)) {
    //                 return $this->sendResponse(new ApplicationResource($application), 'Already Scanned.');
    //             }
    //             if ($application->exam_date_check === true && $application->venue_check === true) {
    //                 $application->select('serial_no', 'user_id', 'scanned_at')->update(['user_id' => user()->id, 'scanned_at' => now()]);
    //             }

    //             return $this->sendResponse(new ApplicationResource($application), 'Applicant info.');
    //         } else {
    //             return $this->sendError('No Data Found.', [], 404);
    //         }
    //     } catch (\Exception $e) {
    //         return $this->sendError('Server Error.', ['error' => $e->getMessage()], 500);
    //     }
    // }

    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'id' => 'required|exists:applications,id',
    //         'status' => 'required|in:yes,no',
    //     ]);

    //     if ($validator->fails()) {
    //         return $this->sendError('Validation Error.', $validator->errors(), 422);
    //     }

    //     $validated = $validator->validated();
    //     $application = Application::findOrFail($validated['id']);

    //     // Check exam date & venue
    //     if ($this->examDateCheck($application) !== true) {
    //         return $this->sendError('Exam date mismatch.', [], 422);
    //     }
    //     if ($this->venueCheck($application) !== true) {
    //         return $this->sendError('Venue mismatch.', [], 403);
    //     }

    //     // Update based on status
    //     $updateData = $validated['status'] === 'yes'
    //         ? ['is_gate_entry' => 1]
    //         : ['is_gate_entry' => 0];

    //     $application->update($updateData);

    //     $message = $validated['status'] === 'yes'
    //         ? 'Application accepted.'
    //         : 'Application rejected.';

    //     return $this->sendResponse($validated, $message);
    // }

    /**
     * Total count of applicants
     */
    public function count()
    {

        if (user()->exam_type == ExamType::SAILOR) {
            $data['allApplicationsByUser'] = Application::where('user_id', user()->id)->count();
            $data['todayApplicationsByUser'] = Application::where('user_id', user()->id)->whereDate('scanned_at', now())->count();
        // }
        //     $teams = [
        //         'A' => team('a'),
        //         'B' => team('b'),
        //         'C' => team('c'),
        //     ];
        //     if (user()->team == 'A' && isset($teams['A'])) {
        //         $data[] = [
        //             'team' => 'A',
        //             'todayApplicants' => $this->todayApplicant($teams['A']),
        //             'todayScannedApplicantsByUser' => $this->todayScannedApplicant($teams['A']),
        //         ];
        //     } elseif (user()->team == 'B' && isset($teams['B'])) {
        //         $data[] = [
        //             'team' => 'B',
        //             'todayApplicants' => $this->todayApplicant($teams['B']),
        //             'todayScannedApplicantsByUser' => $this->todayScannedApplicant($teams['B']),
        //         ];
        //     } elseif (user()->team == 'C' && isset($teams['C'])) {
        //         $data[] = [
        //             'team' => 'C',
        //             'todayApplicants' => $this->todayApplicant($teams['C']),
        //             'todayScannedApplicantsByUser' => $this->todayScannedApplicant($teams['C']),
        //         ];
        //     }

        } elseif (user()->exam_type == ExamType::OFFICER) {
            $data['allApplicationsByUser'] = ApplicationUrl::where('user_id', user()->id)->count();
            $data['todayApplicationsByUser'] = ApplicationUrl::where('user_id', user()->id)->whereDate('scanned_at', now())->count();
        }

        return $this->sendResponse($data, 'Applicants count.');
    }

    public function todayApplicant(array $districts)
    {
        return Application::whereDate('exam_date', now())
            ->whereIn('eligible_district', $districts)
            ->count();
    }

    public function todayScannedApplicant(array $districts)
    {
        $query = Application::whereDate('exam_date', now())
            ->whereIn('eligible_district', $districts)
            ->whereDate('scanned_at', now());

        if (user()->role_id == 7) {
            $query->where('user_id', user()->id);
        }

        return $query->count();
    }

    /**
     * Pre Medical Count
     */
    public function preMedicalCount()
    {
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
