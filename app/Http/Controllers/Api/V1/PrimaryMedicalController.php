<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use App\Traits\ApplicationValidationTrait;
use DB;
use Illuminate\Http\Request;

class PrimaryMedicalController extends BaseController
{
    use ApplicationValidationTrait;

    public function passStatus(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required|exists:applications,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        try {
            DB::beginTransaction();
            $application = Application::select('id', 'is_gate_entry', 'exam_date', 'district', 'is_medical_pass', 'p_m_remark', 'scanned_at')->findOrFail($request->id);

            // Check gate entry, exam date & venue
            if (is_null($application->scanned_at)) {
                return $this->sendError('Applicant has not completed gate entry. Please ensure gate entry is done before proceeding.', [], 422);
            }
            if ($this->examDateCheck($application) !== true) {
                return $this->sendError('Exam date mismatch.', [], 403);
            }
            if ($this->venueCheck($application) !== true) {
                return $this->sendError('Venue mismatch.', [], 403);
            }

            $application->update(['is_medical_pass' => 1, 'p_m_remark' => null]);
            DB::commit();

            return $this->sendResponse(new ApplicationResource($application), 'Primary medical status updated.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->sendError('Something went wrong.', [$e->getMessage()], 500);
        }
    }

    public function failStatus(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required|exists:applications,id',
            'p_m_remark' => 'nullable|string|max:160',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        try {
            DB::beginTransaction();
            $application = Application::select('id', 'exam_date', 'district', 'is_medical_pass', 'p_m_remark', 'scanned_at')->findOrFail($request->id);

            // Check exam date & venue
            if (is_null($application->scanned_at)) {
                return $this->sendError('Applicant has not completed gate entry. Please ensure gate entry is done before proceeding.', [], 422);
            }
            if ($this->examDateCheck($application) !== true) {
                return $this->sendError('Exam date mismatch.', [], 422);
            }
            if ($this->venueCheck($application) !== true) {
                return $this->sendError('Venue mismatch.', [], 403);
            }

            $application->update(['is_medical_pass' => 0, 'p_m_remark' => $request->p_m_remark]);
            DB::commit();

            return $this->sendResponse(new ApplicationResource($application), 'Primary medical status updated.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->sendError('Something went wrong.', [$e->getMessage()], 500);
        }
    }
}
