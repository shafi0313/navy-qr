<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Application;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Resources\ApplicationResource;
use App\Http\Resources\ApplicationUrlResource;
use Illuminate\Validation\ValidationException;
use App\Http\Requests\StoreApplicationUrlRequest;
use App\Http\Controllers\Api\V1\BaseController as BaseController;

class ApplicationController extends BaseController
{
    public function index()
    {
        $roleId = user()->role_id;
        $query = Application::query();

        switch ($roleId) {
            case 1: // Admin
                $query->leftJoin('exam_marks', 'applications.id', '=', 'exam_marks.application_id')
                    ->select(
                        'applications.id',
                        'applications.candidate_designation',
                        'applications.serial_no',
                        'applications.eligible_district',
                        'applications.name',
                        'applications.is_medical_pass',
                        'applications.is_final_pass',
                        'applications.remark',

                        'exam_marks.bangla',
                        'exam_marks.english',
                        'exam_marks.math',
                        'exam_marks.science',
                        'exam_marks.general_knowledge',
                    )
                    ->selectRaw(
                        '(exam_marks.bangla +
                            exam_marks.english +
                            exam_marks.math +
                            exam_marks.science +
                            exam_marks.general_knowledge) as total_marks'
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
                        'applications.id',
                        'applications.candidate_designation',
                        'applications.serial_no',
                        'applications.eligible_district',
                        'applications.name',
                        'applications.is_medical_pass',
                        'applications.is_final_pass',
                        // 'applications.',
                        'exam_marks.bangla',
                        'exam_marks.english',
                        'exam_marks.math',
                        'exam_marks.science',
                        'exam_marks.general_knowledge',
                    )
                    ->selectRaw(
                        '(exam_marks.bangla +
                            exam_marks.english +
                            exam_marks.math +
                            exam_marks.science +
                            exam_marks.general_knowledge) as total_marks'
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
                        'applications.id',
                        'applications.candidate_designation',
                        'applications.serial_no',
                        'applications.eligible_district',
                        'applications.name',
                        'applications.is_medical_pass',
                        'applications.is_final_pass',
                        // 'applications.',
                        'exam_marks.bangla',
                        'exam_marks.english',
                        'exam_marks.math',
                        'exam_marks.science',
                        'exam_marks.general_knowledge',
                    )
                    ->selectRaw(
                        '(exam_marks.bangla +
                            exam_marks.english +
                            exam_marks.math +
                            exam_marks.science +
                            exam_marks.general_knowledge) as total_marks'
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
                        'applications.id',
                        'applications.candidate_designation',
                        'applications.serial_no',
                        'applications.eligible_district',
                        'applications.name',
                        'applications.is_medical_pass',
                        'applications.is_final_pass',
                        // 'applications.',
                        'exam_marks.bangla',
                        'exam_marks.english',
                        'exam_marks.math',
                        'exam_marks.science',
                        'exam_marks.general_knowledge',
                    )
                    ->selectRaw(
                        '(exam_marks.bangla +
                            exam_marks.english +
                            exam_marks.math +
                            exam_marks.science +
                            exam_marks.general_knowledge) as total_marks,
                            exam_marks.viva as total_viva'
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
        $application = Application::whereSerialNo($serialNo)->first();

        if($application){
            $application->update(['scanned_at' => now()]);
        }

        return $this->sendResponse(new ApplicationResource($application), 'Applicant info.');
    }


    public function store(StoreApplicationUrlRequest $request)
    {

        try {

            return $this->sendResponse(new ApplicationUrlResource($applicationUrl), 'Applicant info successfully inserted.');
        } catch (\Exception $e) {
            return $this->sendError('Validation Error.', $data->errors());
        }
    }

    public function medicalPassStatus(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required|exists:application_urls,id',
            'is_medical_pass' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $applicationUrl = Application::with([
            'application:id,application_url_id,post,batch,roll,name'
        ])->select('id', 'url', 'is_medical_pass')->findOrFail($request->id);

        $applicationUrl->update(['is_medical_pass' => $request->is_medical_pass]);

        return $this->sendResponse(new ApplicationUrlResource($applicationUrl), 'Primary medical status updated.');
    }
}
