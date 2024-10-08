<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ApplicationUrlResource;
use App\Http\Resources\ApplicationShowResource;
use App\Http\Controllers\Api\V1\BaseController as BaseController;

class ApplicationController extends BaseController
{
    use ApplicationTrait;

    public function index()
    {
        $roleId = user()->role_id;
        $query = Application::query();
        switch ($roleId) {
            case 1: // Admin
                $query->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->applicationColumns(), $this->examColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->orderBy('total_marks', 'desc');
                break;
            case 2: // Normal User
                $query->select('id', 'candidate_designation', 'serial_no', 'name', 'eligible_district');
                break;
            case 3: // Primary Medical
                $query->select('id', 'candidate_designation', 'serial_no', 'name', 'eligible_district', 'is_medical_pass');
                break;
            case 4: // Written
                $query->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->applicationColumns(), $this->examColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->orderBy('total_marks', 'desc');
                break;
            case 5: // Final Medical
                $query->with(['examMark:id,application_id,bangla,english,math,science,general_knowledge'])
                    ->whereHas('examMark', function ($query) {
                        $query->where('bangla', '>=', 8)
                            ->where('english', '>=', 8)
                            ->where('math', '>=', 8)
                            ->where('science', '>=', 8)
                            ->where('general_knowledge', '>=', 8);
                    })
                    ->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->applicationColumns(), $this->examColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->orderBy('total_marks', 'desc');
                break;
            case 6: // Viva / Final Selection
                $query->with(['examMark:id,application_id,bangla,english,math,science,general_knowledge,viva'])
                    ->whereHas('application.examMark', function ($query) {
                        $query->where('bangla', '>=', 8)
                            ->where('english', '>=', 8)
                            ->where('math', '>=', 8)
                            ->where('science', '>=', 8)
                            ->where('general_knowledge', '>=', 8);
                    })->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        array_merge($this->applicationColumns(), $this->examColumns())
                    )
                    ->selectRaw(
                        $this->examSumColumns()
                    )
                    ->orderBy('total_viva', 'desc')
                    ->orderBy('total_marks', 'desc');
                break;
        }

        $applications = $query->get();

        return $this->sendResponse(ApplicationResource::collection($applications), 'Applicants list.');
    }

    public function show($serialNo)
    {
        $application = Application::where('serial_no', $serialNo)->first();

        if ($application) {
            $application->update(['scanned_at' => now()]);
            return $this->sendResponse(new ApplicationShowResource($application), 'Applicant info.');
        } else {
            return $this->sendError('No Data Found.', [], 404);
        }
    }

    public function count()
    {
        $data['allApplications'] = Application::count();
        $data['todayApplications'] = Application::whereDate('scanned_at', now())->count();

        return $this->sendResponse($data, 'Applicants count.');
    }
}
