<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Resources\ApplicationResource;
use App\Http\Controllers\Api\V1\BaseController as BaseController;

class FinalMedicalController extends BaseController
{
    public function passStatus(Request $request)
    {
        if (!in_array(user()->role_id, [1, 2, 3])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        $validator = \Validator::make($request->all(), [
            'id' => 'required|exists:applications,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $application = Application::select('id', 'is_final_pass')->findOrFail($request->id);
        $application->update(['is_final_pass' => 1]);
        return $this->sendResponse(new ApplicationResource($application), 'Primary medical status updated.');
    }

    public function failStatus(Request $request)
    {
        if (!in_array(user()->role_id, [1, 2, 3])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        $validator = \Validator::make($request->all(), [
            'id' => 'required|exists:applications,id',
            'f_m_remark' => 'required|string|max:160',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $application = Application::select('id', 'is_final_pass')->findOrFail($request->id);
        $application->update(['is_final_pass' => 0, 'f_m_remark' => $request->f_m_remark]);
        return $this->sendResponse(new ApplicationResource($application), 'Primary medical status updated.');
    }
}
