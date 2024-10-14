<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ResultController;
use App\Http\Controllers\Admin\ExamMarkController;
use App\Http\Controllers\Admin\VivaMarkController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\FinalMedicalController;
use App\Http\Controllers\Setting\AppDbBackupController;
use App\Http\Controllers\Admin\ApplicationUrlController;
use App\Http\Controllers\Admin\PrimaryMedicalController;
use App\Http\Controllers\Admin\WrittenMarkImportController;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');


// App DB Backup
Route::controller(AppDbBackupController::class)->prefix('app-db-backup')->group(function(){
    Route::get('/password', 'password')->name('backup.password');
    Route::post('/checkPassword', 'checkPassword')->name('backup.checkPassword');
    Route::get('/confirm', 'index')->name('backup.index');
    Route::post('/backup-file', 'backupFiles')->name('backup.files');
    Route::post('/backup-db', 'backupDb')->name('backup.db');
    Route::post('/backup-download/{name}/{ext}', 'downloadBackup')->name('backup.download');
    Route::post('/backup-delete/{name}/{ext}', 'deleteBackup')->name('backup.delete');
});

// Global Ajax Route
Route::get('select-2-ajax', [AjaxController::class, 'select2'])->name('select2');
Route::post('response', [AjaxController::class, 'response'])->name('ajax');

Route::resource('/roles', RoleController::class)->except(['show','create']);
Route::patch('/roles/is-active/{role}', [RoleController::class, 'status'])->name('roles.is_active');

Route::resource('/admin-users', AdminUserController::class)->except(['show','create']);
Route::patch('/admin-users/is-active/{user}', [AdminUserController::class, 'status'])->name('admin_users.is_active');

Route::resource('/my-profiles', MyProfileController::class)->only(['index','edit']);

Route::resource('/application-urls', ApplicationUrlController::class)->only(['index']);
Route::resource('/applications', ApplicationController::class)->except(['show']);

Route::resource('/exam-marks', ExamMarkController::class)->only(['index','store']);
Route::get('/exam-marks/modal-store/{applicantId}', [ExamMarkController::class, 'modalStore'])->name('exam_marks.modal_store');

Route::resource('/written-mark-imports', WrittenMarkImportController::class)->except(['create', 'show']);
    Route::controller(WrittenMarkImportController::class)->prefix('written-mark-import')->name('written_mark_imports.')->group(function () {
        Route::post('/imports', 'import')->name('import');
        Route::get('/imports/all-delete', 'allDelete')->name('all_deletes');
    });




Route::get('primary-medicals', [PrimaryMedicalController::class, 'index'])->name('primary_medicals.index');
Route::patch('primary-medicals/pass', [PrimaryMedicalController::class, 'pass'])->name('primary_medicals.pass');
Route::patch('primary-medicals/fail', [PrimaryMedicalController::class, 'fail'])->name('primary_medicals.fail');

Route::get('final-medicals', [FinalMedicalController::class, 'index'])->name('final_medicals.index');
Route::patch('final-medicals/pass', [FinalMedicalController::class, 'pass'])->name('final_medicals.pass');
Route::patch('final-medicals/fail', [FinalMedicalController::class, 'fail'])->name('final_medicals.fail');

Route::resource('/viva-marks', VivaMarkController::class)->only(['index','store']);
Route::get('/viva-marks/modal-store/{applicantId}', [VivaMarkController::class, 'modalStore'])->name('viva_marks.modal_store');

Route::resource('/results', ResultController::class)->only(['index']);
