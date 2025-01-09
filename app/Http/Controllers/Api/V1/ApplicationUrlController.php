<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\BaseController as BaseController;
use App\Http\Resources\ApplicationUrlResource;
use App\Models\ApplicationUrl;
use Illuminate\Http\Request;

class ApplicationUrlController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function showOrStore(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'url' => 'required|url',
        ]);
        $validatedData['scanned_at'] = now();

        $url = $validatedData['url'];

        $applicationUrl = ApplicationUrl::where('url', $url)->first();
        if($applicationUrl) {
            return $this->sendResponse(new ApplicationUrlResource($applicationUrl), 'ULR already exists.');
        } else {
            $applicationUrl = ApplicationUrl::create($validatedData);
            return $this->sendResponse(new ApplicationUrlResource($applicationUrl), 'URL created.');
        }

        // $applicationUrl = ApplicationUrl::where('url', $url)->firstOr(function () use ($validatedData) {
        //     return ApplicationUrl::create($validatedData);
        // });

        // return $this->sendResponse(new ApplicationUrlResource($applicationUrl), 'Applicant info.');
    }
}
