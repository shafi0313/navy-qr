<?php

use App\Exports\ApplicantsExport;
use App\Http\Controllers\Admin\ActiveUserController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AppInstructionController;
use App\Http\Controllers\Admin\ApplicantCountController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\ApplicationImportantController;
use App\Http\Controllers\Admin\ApplicationSearchController;
use App\Http\Controllers\Admin\ApplicationUrlController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ExamMarkController;
use App\Http\Controllers\Admin\FinalMedicalController;
use App\Http\Controllers\Admin\ImportantApplicationController;
use App\Http\Controllers\Admin\PrimaryMedicalController;
use App\Http\Controllers\Admin\RemoveDataController;
use App\Http\Controllers\Admin\Reports\DailyStateReportController;
use App\Http\Controllers\Admin\ResetDataController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SmsController;
use App\Http\Controllers\Admin\SpecialityController;
use App\Http\Controllers\Admin\TeamF\Encl1DeucSailorController;
use App\Http\Controllers\Admin\TeamF\Encl2NonDeucSailorController;
use App\Http\Controllers\Admin\TeamF\TeamFDataController;
use App\Http\Controllers\Admin\TeamF\TeamFImportDataController;
use App\Http\Controllers\Admin\VivaMarkController;
use App\Http\Controllers\Admin\WrittenMarkImportController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\MyProfileController;
use App\Services\SeederWriter;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/seed', function (SeederWriter $writer) {
    $writer->generate();

    return 'Seeders generated successfully!';
});

Route::controller(RemoveDataController::class)->prefix('rm-remove')->name('rm.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('sd', 'sailor')->name('sd');
});

Route::controller(ActiveUserController::class)->prefix('active-users')->name('active_users.')->group(function () {
    Route::get('/', 'activeUsers')->name('index');
    Route::post('/logout-user/{id}', 'logoutUser')->name('logout');
    Route::post('/logout-all-users', 'logoutAllUsers')->name('logout_all');
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Global Ajax Route
Route::get('select-2-ajax', [AjaxController::class, 'select2'])->name('select2');
Route::post('response', [AjaxController::class, 'response'])->name('ajax');

Route::resource('/sms', SmsController::class)->only(['index']);

Route::resource('/roles', RoleController::class)->except(['show', 'create']);
Route::patch('/roles/is-active/{role}', [RoleController::class, 'status'])->name('roles.is_active');

Route::resource('/admin-users', AdminUserController::class)->except(['show', 'create']);
Route::patch('/admin-users/is-active/{user}', [AdminUserController::class, 'status'])->name('admin_users.is_active');

Route::resource('/app-instructions', AppInstructionController::class)->except(['show', 'create']);
Route::get('/app-instructions/show/{menuName}', [AppInstructionController::class, 'show'])->name('app_instructions.show');
Route::patch('/app-instructions/is-active/{AppInstruction}', [AppInstructionController::class, 'status'])->name('app_instructions.is_active');

Route::resource('/specialities', SpecialityController::class)->except(['show', 'create']);
Route::patch('/specialities/is-active/{Speciality}', [SpecialityController::class, 'status'])->name('specialities.is_active');

Route::resource('/my-profiles', MyProfileController::class)->only(['index', 'edit']);

Route::resource('/applications', ApplicationController::class)->except(['show']);
Route::resource('/application-search', ApplicationSearchController::class)->only(['index', 'store', 'edit']);
Route::get('/application-search/show/{id}', [ApplicationSearchController::class, 'show'])->name('application_search.show');
// Route::post('application-search/store', [ApplicationSearchController::class, 'store'])->name('primary_medicals.store');

Route::resource('/reset-data', ResetDataController::class)->only(['index', 'store']);
Route::get('/reset-data/show/{id}', [ResetDataController::class, 'show'])->name('reset_data.show');

Route::resource('/important-application-imports', ApplicationImportantController::class)->except(['create', 'show']);
Route::controller(ApplicationImportantController::class)->prefix('important-application-import')->name('important_application_imports.')->group(function () {
    Route::post('/imports', 'import')->name('import');
    Route::get('/imports/all-delete', 'allDelete')->name('all_deletes');
});

Route::resource('/exam-marks', ExamMarkController::class)->only(['index', 'store']);
Route::get('/exam-marks/modal-store/{applicantId}', [ExamMarkController::class, 'modalStore'])->name('exam_marks.modal_store');

Route::resource('/written-mark-imports', WrittenMarkImportController::class)->except(['create', 'show']);
Route::controller(WrittenMarkImportController::class)->prefix('written-mark-import')->name('written_mark_imports.')->group(function () {
    Route::post('/imports', 'import')->name('import');
    Route::post('/check', 'check')->name('check');
    Route::post('/all-delete', 'allDelete')->name('all_deletes');
});

Route::get('primary-medicals', [PrimaryMedicalController::class, 'index'])->name('primary_medicals.index');
Route::get('primary-medicals/modal-show/{applicantId}', [PrimaryMedicalController::class, 'modalShow'])->name('primary_medicals.modal_show');
Route::post('primary-medicals/store', [PrimaryMedicalController::class, 'store'])->name('primary_medicals.store');
// Route::patch('primary-medicals/pass', [PrimaryMedicalController::class, 'pass'])->name('primary_medicals.pass');
// Route::put('primary-medicals/unfit-store', [PrimaryMedicalController::class, 'unfitStore'])->name('primary_medicals.unfit_store');
// Route::get('/primary-medicals/unfit/{application}', [PrimaryMedicalController::class, 'unfitModal'])->name('primary_medicals.unfit');

Route::get('final-medicals', [FinalMedicalController::class, 'index'])->name('final_medicals.index');
Route::get('final-medicals/modal-show/{applicantId}', [FinalMedicalController::class, 'modalShow'])->name('final_medicals.modal_show');
Route::post('final-medicals/store', [FinalMedicalController::class, 'store'])->name('final_medicals.store');
// Route::patch('final-medicals/pass', [FinalMedicalController::class, 'pass'])->name('final_medicals.pass'); // This route is not used
// Route::put('final-medicals/fit-store', [FinalMedicalController::class, 'fitStore'])->name('final_medicals.fit_store');
// Route::put('final-medicals/unfit-store', [FinalMedicalController::class, 'unfitStore'])->name('final_medicals.unfit_store');
// Route::get('/final-medicals/fit/{application}', [FinalMedicalController::class, 'fitModal'])->name('final_medicals.fit');
// Route::get('/final-medicals/unfit/{application}', [FinalMedicalController::class, 'unfitModal'])->name('final_medicals.unfit');

Route::resource('/viva-marks', VivaMarkController::class)->only(['index', 'store']);
Route::get('/viva-marks/modal-store/{applicantId}', [VivaMarkController::class, 'modalStore'])->name('viva_marks.modal_store');

Route::resource('important-applications', ImportantApplicationController::class)->only(['index', 'store']);

Route::resource('/results', ResultController::class)->only(['index']);
Route::get('/results/export-excel', [ResultController::class, 'exportExcel'])->name('results.export_excel');

Route::resource('/application-urls', ApplicationUrlController::class)->only(['index']);

Route::get('applicant-count', [ApplicantCountController::class, 'index'])->name('applicant_count');

Route::get('/export-applicants', function () {
    return Excel::download(new ApplicantsExport, 'applicants.xlsx');
});

// Team F Routes
Route::resource('/team-f-data-imports', TeamFImportDataController::class)->except(['create', 'show']);
Route::resource('/team-f-datum', TeamFDataController::class)->only(['index', 'edit', 'update', 'destroy']);
Route::prefix('team-f')->name('team_f.')->group(function () {
    Route::controller(TeamFImportDataController::class)->prefix('team-f-data-imports')->name('data_imports.')->group(function () {
        Route::post('/imports', 'import')->name('import');
        Route::get('/single-store-view', 'singleStoreView')->name('single_store_view');
        Route::post('/single-store', 'singleStore')->name('single_store');
        Route::get('/imports/all-delete', 'allDelete')->name('all_deletes');
    });
    Route::get('encl1-deuc-sailor/{type?}', [Encl1DeucSailorController::class, 'report'])->name('encl1_deuc_sailor.report');
    Route::get('encl1-deuc-sailor/export/excel', [Encl1DeucSailorController::class, 'exportExcel'])->name('encl1_deuc_sailor.export_excel');

    Route::controller(Encl2NonDeucSailorController::class)->prefix('encl2-non-deuc-sailor')->name('encl2_non_deuc_sailor.')->group(function () {
        Route::get('{type?}', 'report')->name('report');
        Route::get('export/excel', 'exportExcel')->name('export_excel');
        // Route::get('edit/modal-show', 'enclEditModal')->name('edit_modal');
        // Route::post('edit/update-encl-remark', 'updateEnclRemark')->name('update_encl_remark');
    });
    // Route::get('encl2-non-deuc-sailor/{type?}', [Encl2NonDeucSailorController::class, 'report'])->name('encl2_non_deuc_sailor.report');
    // Route::get('encl2-non-deuc-sailor/export/excel', [Encl2NonDeucSailorController::class, 'exportExcel'])->name('encl2_non_deuc_sailor.export_excel');
});

// Reports Route
Route::prefix('reports')->name('reports.')->group(function () {
    Route::controller(DailyStateReportController::class)->prefix('daily-state')->name('daily_state.')->group(function () {
        Route::get('/', 'select')->name('select');
        Route::get('/report', 'report')->name('report');
        Route::get('/export/excel/{startDate}/{endDate}/{team}', 'exportExcel')->name('export_excel');
    });
});
