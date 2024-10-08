<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ApplicationController;
use App\Http\Controllers\Api\V1\FinalMedicalController;
use App\Http\Controllers\Api\V1\ApplicationUrlController;
use App\Http\Controllers\Api\V1\PrimaryMedicalController;



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

        // Final Medical
        Route::post('/applications/primary-medical/pass', [PrimaryMedicalController::class, 'passStatus']);
        Route::post('/applications/primary-medical/fail', [PrimaryMedicalController::class, 'failStatus']);

        // Final Medical
        Route::post('/applications/final-medical/pass', [FinalMedicalController::class, 'passStatus']);
        Route::post('/applications/final-medical/fail', [FinalMedicalController::class, 'failStatus']);


        Route::post('/logout', [AuthController::class, 'logout']);

    });
});
