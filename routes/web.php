<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CertificateLogController;


Route::get('/certificate-logs', [CertificateLogController::class, 'index']);
Route::get('/certificate-logs/{id}/pdf', [CertificateLogController::class, 'download']);

Route::resource('certificates', CertificateController::class);

Route::get('/certificates/baptism/search', [CertificateController::class, 'search'])
    ->name('certificates.baptism.search');

Route::get('/certificates/baptism/member/{id}', [CertificateController::class, 'baptismForm'])
    ->name('certificates.baptism.member');

Route::post('/certificates/baptism/print', [CertificateController::class, 'printBaptism'])
    ->name('certificates.baptism.print');

Route::get('/', [DashboardController::class, 'index']);

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
Route::resource('/members', MemberController::class);

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index']);


Route::post('/task/store', [TaskController::class, 'store']);
Route::post('/task/toggle/{id}', [TaskController::class, 'toggle']);
Route::delete('/task/delete/{id}', [TaskController::class, 'destroy']);