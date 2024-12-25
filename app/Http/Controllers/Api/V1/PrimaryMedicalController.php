<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\BaseController as BaseController;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Illuminate\Http\Request;

class PrimaryMedicalController extends BaseController
{
    public function passStatus(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'id' => 'required|exists:applications,id',
            // 'is_medical_pass' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $application = Application::select('id', 'is_medical_pass', 'p_m_remark')->findOrFail($request->id);
        $application->update(['is_medical_pass' => 1, 'p_m_remark' => null]);

        return $this->sendResponse(new ApplicationResource($application), 'Primary medical status updated.');
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
        $application = Application::select('id', 'is_medical_pass')->findOrFail($request->id);
        $application->update(['is_medical_pass' => 0, 'p_m_remark' => $request->p_m_remark]);

        return $this->sendResponse(new ApplicationResource($application), 'Primary medical status updated.');
    }
}
