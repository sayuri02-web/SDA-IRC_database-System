<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CertificateLogController;
use App\Http\Controllers\DedicationCertificateController;
use App\Http\Controllers\BaptismCertificateController;
use App\Http\Controllers\MembershipCertificateController;
use App\Http\Controllers\CounselingCertificateController;
use App\Http\Controllers\StudentCertificatesController;
use App\Http\Controllers\GoodMoralCertificatesController;
use App\Http\Controllers\MembersAffiliateCertificateController;
use App\Http\Controllers\LeadersDirectoryController;
use App\Http\Controllers\WebsiteManagementController;


Route::get('/certificate-logs', [CertificateLogController::class, 'index']);
Route::get('/certificate-logs/{id}/pdf', [CertificateLogController::class, 'download']);

Route::resource('certificates', CertificateController::class);

Route::get('/certificates/baptism/search', [CertificateController::class, 'search'])
    ->name('certificates.baptism.search');

Route::get('/certificates/baptism/member/{id}', [CertificateController::class, 'baptismForm'])
    ->name('certificates.baptism.member');

Route::post('/certificates/baptism/print', [BaptismCertificateController::class, 'print'])
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

// Membership Certificate routes
Route::get('/certificates/membership/search', [MembershipCertificateController::class, 'search'])
    ->name('certificates.membership.search');
Route::get('/certificates/membership/member/{id}', [MembershipCertificateController::class, 'form'])
    ->name('certificates.membership.member');
Route::post('/certificates/membership/print', [MembershipCertificateController::class, 'print'])
    ->name('certificates.membership.print');

// Counseling Certificate routes
Route::get('/certificates/counseling/search', [CounselingCertificateController::class, 'search'])
    ->name('certificates.counseling.search');
Route::get('/certificates/counseling/member/{id}', [CounselingCertificateController::class, 'form'])
    ->name('certificates.counseling.member');
Route::post('/certificates/counseling/print', [CounselingCertificateController::class, 'print'])
    ->name('certificates.counseling.print');

// Student Certificate routes
Route::get('/certificates/student/search', [StudentCertificatesController::class, 'search'])
    ->name('certificates.student.search');
Route::get('/certificates/student/member/{id}', [StudentCertificatesController::class, 'form'])
    ->name('certificates.student.member');
Route::post('/certificates/student/print', [StudentCertificatesController::class, 'print'])
    ->name('certificates.student.print');

// Good Moral Certificate routes
Route::get('/certificates/goodmoral/search', [GoodMoralCertificatesController::class, 'search'])
    ->name('certificates.goodmoral.search');
Route::get('/certificates/goodmoral/member/{id}', [GoodMoralCertificatesController::class, 'form'])
    ->name('certificates.goodmoral.member');
Route::post('/certificates/goodmoral/print', [GoodMoralCertificatesController::class, 'print'])
    ->name('certificates.goodmoral.print');

// Members Affiliate Certificate routes
Route::get('/certificates/affiliate/search', [MembersAffiliateCertificateController::class, 'search'])
    ->name('certificates.affiliate.search');
Route::get('/certificates/affiliate/member/{id}', [MembersAffiliateCertificateController::class, 'form'])
    ->name('certificates.affiliate.member');
Route::post('/certificates/affiliate/print', [MembersAffiliateCertificateController::class, 'print'])
    ->name('certificates.affiliate.print');

//Pastor Message routes
Route::get(
    '/website-management/pastors',
    [WebsiteManagementController::class, 'pastors']
)->name('website-management.pastors');

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
Route::prefix('website-management')
    ->name('website-management.')
    ->group(function () {

        Route::get('/', [WebsiteManagementController::class, 'index'])
            ->name('index');

        Route::get('/pastor-message', [WebsiteManagementController::class, 'pastorMessage'])
            ->name('pastor-message');

        Route::post('/pastor-message', [WebsiteManagementController::class, 'savePastorMessage'])
            ->name('pastor-message.save');

        Route::get('/events-announcements', [WebsiteManagementController::class, 'eventsAnnouncements'])
            ->name('events-announcements');

        // Events API
        Route::get('/events/list', [WebsiteManagementController::class, 'getEvents'])->name('events.list');
        Route::post('/events', [WebsiteManagementController::class, 'storeEvent'])->name('events.store');
        Route::put('/events/{id}', [WebsiteManagementController::class, 'updateEvent'])->name('events.update');
        Route::delete('/events/{id}', [WebsiteManagementController::class, 'destroyEvent'])->name('events.destroy');
        Route::post('/events/{id}/toggle', [WebsiteManagementController::class, 'toggleEvent'])->name('events.toggle');

        // Announcements API
        Route::get('/announcements/list', [WebsiteManagementController::class, 'getAnnouncements'])->name('announcements.list');
        Route::post('/announcements', [WebsiteManagementController::class, 'storeAnnouncement'])->name('announcements.store');
        Route::put('/announcements/{id}', [WebsiteManagementController::class, 'updateAnnouncement'])->name('announcements.update');
        Route::delete('/announcements/{id}', [WebsiteManagementController::class, 'destroyAnnouncement'])->name('announcements.destroy');
        Route::post('/announcements/{id}/toggle', [WebsiteManagementController::class, 'toggleAnnouncement'])->name('announcements.toggle');

        Route::get('/ministries', [WebsiteManagementController::class, 'ministries'])
            ->name('ministries');

        Route::get('/gallery', [WebsiteManagementController::class, 'gallery'])
            ->name('gallery');
    });

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::get('/api/dashboard-data', [DashboardController::class, 'apiData']);

Route::resource('/members', MemberController::class);


Route::post('/task/store', [TaskController::class, 'store']);
Route::post('/task/toggle/{id}', [TaskController::class, 'toggle']);
Route::post('/task/update-status', [TaskController::class, 'updateStatus']);
Route::get('/task/dates', [TaskController::class, 'getDates']);
Route::get('/task/by-date', [TaskController::class, 'getByDate']);
Route::delete('/task/delete/{id}', [TaskController::class, 'destroy']);

Route::post('/logout', function (Illuminate\Http\Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('website.home');
})->name('logout');

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
Route::get('/leaders-directory/search', [LeadersDirectoryController::class, 'search'])->name('leaders-directory.search');
