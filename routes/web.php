<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CertificateLogController;
use App\Http\Controllers\DedicationCertificateController;
use App\Http\Controllers\LeadersDirectoryController;


Route::get('/certificate-logs', [CertificateLogController::class, 'index']);
Route::get('/certificate-logs/{id}/pdf', [CertificateLogController::class, 'download']);

Route::resource('certificates', CertificateController::class);

Route::get('/certificates/baptism/search', [CertificateController::class, 'search'])
    ->name('certificates.baptism.search');

Route::get('/certificates/baptism/member/{id}', [CertificateController::class, 'baptismForm'])
    ->name('certificates.baptism.member');

Route::post('/certificates/baptism/print', [CertificateController::class, 'printBaptism'])
    ->name('certificates.baptism.print');

// Dedication Certificate routes
Route::get('/certificates/dedication/search', [DedicationCertificateController::class, 'search'])
    ->name('certificates.dedication.search');
Route::get('/certificates/dedication/member/{id}', [DedicationCertificateController::class, 'form'])
    ->name('certificates.dedication.member');
Route::post('/certificates/dedication/print', [DedicationCertificateController::class, 'print'])
    ->name('certificates.dedication.print');
Route::get('/certificates/dedication/history', [DedicationCertificateController::class, 'history'])
    ->name('certificates.dedication.history');

Route::get('/', [DashboardController::class, 'index']);

// TEMPORARY: run migrations via browser (DELETE AFTER USE)
Route::get('/run-migrate', function () {
    \Artisan::call('migrate', ['--force' => true]);
    return '<pre>' . \Artisan::output() . '</pre>';
});

Route::resource('church', ChurchController::class);

/*
|--------------------------------------------------------------------------
| AJAX JSON ROUTE
|--------------------------------------------------------------------------
*/
Route::get('/members/{id}/json', [MemberController::class, 'showJson']);

/*
|--------------------------------------------------------------------------
| RESOURCE ROUTE
|-------------------------------------------------------------------------- 
*/
Route::get('/website-management', function () {
    return view('website-management.index');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('/members', MemberController::class);


Route::post('/task/store', [TaskController::class, 'store']);
Route::post('/task/toggle/{id}', [TaskController::class, 'toggle']);
Route::post('/task/update-status', [TaskController::class, 'updateStatus']);
Route::get('/task/dates', [TaskController::class, 'getDates']);
Route::get('/task/by-date', [TaskController::class, 'getByDate']);
Route::delete('/task/delete/{id}', [TaskController::class, 'destroy']);


/*
|--------------------------------------------------------------------------
| LEADERS DIRECTORY
|--------------------------------------------------------------------------
*/
Route::get('/leaders-directory', [LeadersDirectoryController::class, 'index'])->name('leaders-directory.index');
Route::post('/leaders-directory/organization', [LeadersDirectoryController::class, 'storeOrganization'])->name('leaders-directory.store-org');
Route::delete('/leaders-directory/organization/{id}', [LeadersDirectoryController::class, 'destroyOrganization'])->name('leaders-directory.destroy-org');
Route::delete('/leaders-directory/member/{id}', [LeadersDirectoryController::class, 'destroyMember'])->name('leaders-directory.destroy-member');
Route::put('/leaders-directory/member/{id}', [LeadersDirectoryController::class, 'updateMember'])->name('leaders-directory.update-member');
