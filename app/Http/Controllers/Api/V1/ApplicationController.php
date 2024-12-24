<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Traits\ApplicationTrait;
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
        $applications = $query->get();

        return $this->sendResponse(ApplicationResource::collection($applications), 'Applicants list.');
    }

    public function show($serialNo)
    {
        $application = Application::with('examMark')->where('serial_no', $serialNo)->first();

        if ($application) {
            if (!is_null($application->scanned_at)) {
                return $this->sendResponse(new ApplicationResource($application), 'Already Scanned.');
            }
            
            $application->update(['scanned_at' => now()]);
            $application->update(['user_id' => user()->id]);
            return $this->sendResponse(new ApplicationResource($application), 'Applicant info.');
        } else {
            return $this->sendError('No Data Found.', [], 404);
        }
    }

    public function count()
    {
        $data['allApplicationsByUser'] = Application::where('user_id', user()->id)->count();
        $data['todayApplicationsByUser'] = Application::where('user_id', user()->id)->whereDate('scanned_at', now())->count();

        return $this->sendResponse($data, 'Applicants count.');
    }
}
