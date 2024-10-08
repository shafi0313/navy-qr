<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ApplicationController;
use App\Http\Controllers\Api\V1\FinalMedicalController;
use App\Http\Controllers\Api\V1\ApplicationUrlController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {
    Route::controller(AuthController::class)->group(function(){
        Route::post('login', 'login');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/applications/count', [ApplicationController::class, 'count']);
        Route::apiResource('applications', ApplicationController::class);
        Route::post('/applications/medical-pass-status', [ApplicationController::class, 'medicalPassStatus']);
        Route::post('/applications/medical-fail-status', [ApplicationController::class, 'medicalFailStatus']);

        // Final Medical
        Route::post('/applications/final-medical-pass-status', [FinalMedicalController::class, 'passStatus']);
        Route::post('/applications/final-medical-fail-status', [FinalMedicalController::class, 'failStatus']);


        Route::post('/logout', [AuthController::class, 'logout']);

    });
});
