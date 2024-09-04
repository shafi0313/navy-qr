<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MyProfileController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Setting\AppDbBackupController;
use App\Http\Controllers\Setting\Permission\RoleController;
use App\Http\Controllers\Setting\Permission\PermissionController;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');


// Role & Permission
Route::post('/role/permission/{role}', [RoleController::class, 'assignPermission'])->name('role.permission');
Route::resource('/role', RoleController::class);
Route::resource('/permission', PermissionController::class);

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


Route::resource('/admin-users', AdminUserController::class)->except(['show','create']);
Route::patch('/admin-users/is-active/{user}', [AdminUserController::class, 'status'])->name('admin_users.is_active');

Route::resource('/my-profiles', MyProfileController::class)->only(['index','edit']);
