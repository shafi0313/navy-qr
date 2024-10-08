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
        if (!in_array(user()->role_id, [1, 3])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        $validator = \Validator::make($request->all(), [
            'id' => 'required|exists:applications,id',
            'is_final_pass' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $application = Application::select('id', 'is_final_pass')->findOrFail($request->id);
        $application->update(['is_final_pass' => $request->is_final_pass]);
        return $this->sendResponse(new ApplicationResource($application), 'Primary medical status updated.');
    }

    public function failStatus(Request $request)
    {
        if (!in_array(user()->role_id, [1, 3])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }if (!in_array(user()->role_id, [1, 3])) {
            return response()->json(['message' => 'You are not authorized to perform this action'], 403);
        }
        $validator = \Validator::make($request->all(), [
            'id' => 'required|exists:applications,id',
            'is_final_pass' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $application = Application::select('id', 'is_final_pass')->findOrFail($request->id);
        $application->update(['is_final_pass' => $request->is_final_pass]);
        return $this->sendResponse(new ApplicationResource($application), 'Primary medical status updated.');
    }
}
