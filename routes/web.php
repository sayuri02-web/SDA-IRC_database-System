<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\ChurchController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskNotificationController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\CertificateLogController;
use App\Http\Controllers\DedicationCertificateController;
use App\Http\Controllers\BaptismCertificateController;
use App\Http\Controllers\MembershipCertificateController;
use App\Http\Controllers\CounselingCertificateController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentCertificatesController;
use App\Http\Controllers\GoodMoralCertificatesController;
use App\Http\Controllers\MembersAffiliateCertificateController;
use App\Http\Controllers\LeadersDirectoryController;
use App\Http\Controllers\WebsiteManagementController;


Route::get('/certificate-logs', [CertificateLogController::class, 'index']);
Route::get('/certificate-logs/{id}/pdf', [CertificateLogController::class, 'download']);

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    if (Auth::check()) return redirect('/dashboard');
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $request->validate([
        'username' => 'required|string',
        'password' => 'required',
    ]);

    if (Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors(['username' => 'Invalid username or password.'])->withInput($request->only('username'));
})->name('login.submit');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

Route::middleware('auth')->group(function () {

// Certificates (all routes - only Admin + Certificate Manager)
Route::middleware('role:certificates')->group(function () {

Route::get('/certificates', [CertificateController::class, 'index'])->name('certificates.index');
Route::get('/certificates/create', [CertificateController::class, 'create'])->name('certificates.create');
Route::get('/certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');
Route::get('/certificates/{certificate}/edit', [CertificateController::class, 'edit'])->name('certificates.edit');
Route::post('/certificates', [CertificateController::class, 'store'])->name('certificates.store');
Route::put('/certificates/{certificate}', [CertificateController::class, 'update'])->name('certificates.update');
Route::delete('/certificates/{certificate}', [CertificateController::class, 'destroy'])->name('certificates.destroy');

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

}); // End certificates middleware group

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

// Church (read - all users; write - admin only)
Route::get('/church', [ChurchController::class, 'index'])->name('church.index');
Route::get('/church/create', [ChurchController::class, 'create'])->name('church.create');
Route::get('/church/{church}', [ChurchController::class, 'show'])->name('church.show');
Route::get('/church/{church}/edit', [ChurchController::class, 'edit'])->name('church.edit');
Route::post('/church', [ChurchController::class, 'store'])->middleware('role:admin')->name('church.store');
Route::put('/church/{church}', [ChurchController::class, 'update'])->middleware('role:admin')->name('church.update');
Route::delete('/church/{church}', [ChurchController::class, 'destroy'])->middleware('role:admin')->name('church.destroy');

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
    ->middleware('role:website-management')
    ->group(function () {

        Route::get('/', [WebsiteManagementController::class, 'index'])
            ->name('index');

        Route::get('/pastor-message', [WebsiteManagementController::class, 'pastorMessage'])
            ->name('pastor-message');

        Route::post('/pastor-message', [WebsiteManagementController::class, 'savePastorMessage'])
            ->middleware('role:website-management')
            ->name('pastor-message.save');

        Route::get('/events-announcements', [WebsiteManagementController::class, 'eventsAnnouncements'])
            ->name('events-announcements');

        // Events API
        Route::get('/events/list', [WebsiteManagementController::class, 'getEvents'])->name('events.list');
        Route::post('/events', [WebsiteManagementController::class, 'storeEvent'])->middleware('role:website-management')->name('events.store');
        Route::put('/events/{id}', [WebsiteManagementController::class, 'updateEvent'])->middleware('role:website-management')->name('events.update');
        Route::delete('/events/{id}', [WebsiteManagementController::class, 'destroyEvent'])->middleware('role:website-management')->name('events.destroy');
        Route::post('/events/{id}/toggle', [WebsiteManagementController::class, 'toggleEvent'])->middleware('role:website-management')->name('events.toggle');

        // Announcements API
        Route::get('/announcements/list', [WebsiteManagementController::class, 'getAnnouncements'])->name('announcements.list');
        Route::post('/announcements', [WebsiteManagementController::class, 'storeAnnouncement'])->middleware('role:website-management')->name('announcements.store');
        Route::put('/announcements/{id}', [WebsiteManagementController::class, 'updateAnnouncement'])->middleware('role:website-management')->name('announcements.update');
        Route::delete('/announcements/{id}', [WebsiteManagementController::class, 'destroyAnnouncement'])->middleware('role:website-management')->name('announcements.destroy');
        Route::post('/announcements/{id}/toggle', [WebsiteManagementController::class, 'toggleAnnouncement'])->middleware('role:website-management')->name('announcements.toggle');

        Route::get('/ministries', [WebsiteManagementController::class, 'ministries'])
            ->name('ministries');

        // Ministries API
        Route::get('/ministries/list', [WebsiteManagementController::class, 'getMinistries'])->name('ministries.list');
        Route::post('/ministries', [WebsiteManagementController::class, 'storeMinistry'])->middleware('role:website-management')->name('ministries.store');
        Route::put('/ministries/{id}', [WebsiteManagementController::class, 'updateMinistry'])->middleware('role:website-management')->name('ministries.update');
        Route::delete('/ministries/{id}', [WebsiteManagementController::class, 'destroyMinistry'])->middleware('role:website-management')->name('ministries.destroy');
        Route::post('/ministries/{id}/toggle', [WebsiteManagementController::class, 'toggleMinistry'])->middleware('role:website-management')->name('ministries.toggle');

        Route::get('/gallery', [WebsiteManagementController::class, 'gallery'])
            ->name('gallery');

        // Gallery API
        Route::get('/gallery/albums', [WebsiteManagementController::class, 'getAlbums'])->name('gallery.albums');
        Route::post('/gallery/albums', [WebsiteManagementController::class, 'storeAlbum'])->middleware('role:website-management')->name('gallery.albums.store');
        Route::put('/gallery/albums/{id}', [WebsiteManagementController::class, 'updateAlbum'])->middleware('role:website-management')->name('gallery.albums.update');
        Route::delete('/gallery/albums/{id}', [WebsiteManagementController::class, 'destroyAlbum'])->middleware('role:website-management')->name('gallery.albums.destroy');
        Route::get('/gallery/{album}', [WebsiteManagementController::class, 'showAlbum'])->name('gallery.album');

        // Gallery Photos API
        Route::get('/gallery/{album}/photos', [WebsiteManagementController::class, 'getPhotos'])->name('gallery.photos');
        Route::post('/gallery/{album}/photos', [WebsiteManagementController::class, 'uploadPhotos'])->middleware('role:website-management')->name('gallery.photos.upload');
        Route::delete('/gallery/photos/{photo}', [WebsiteManagementController::class, 'deletePhoto'])->middleware('role:website-management')->name('gallery.photos.delete');
    });

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// User Management (Admin only)
Route::get('/users', [UserController::class, 'index'])->middleware('role:admin')->name('users.index');
Route::post('/users', [UserController::class, 'store'])->middleware('role:admin')->name('users.store');
Route::put('/users/{id}', [UserController::class, 'update'])->middleware('role:admin')->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->middleware('role:admin')->name('users.destroy');

Route::get('/api/dashboard-data', [DashboardController::class, 'apiData']);

// Members (read - all users; write - admin only)
Route::get('/members', [MemberController::class, 'index'])->name('members.index');
Route::get('/members/create', [MemberController::class, 'create'])->name('members.create');
Route::get('/members/{member}', [MemberController::class, 'show'])->name('members.show');
Route::get('/members/{member}/edit', [MemberController::class, 'edit'])->name('members.edit');
Route::post('/members', [MemberController::class, 'store'])->middleware('role:admin')->name('members.store');
Route::put('/members/{member}', [MemberController::class, 'update'])->middleware('role:admin')->name('members.update');
Route::patch('/members/{member}', [MemberController::class, 'update'])->middleware('role:admin')->name('members.update.patch');
Route::delete('/members/{member}', [MemberController::class, 'destroy'])->middleware('role:admin')->name('members.destroy');


Route::post('/task/store', [TaskController::class, 'store'])->middleware('role:admin');
Route::post('/task/toggle/{id}', [TaskController::class, 'toggle'])->middleware('role:admin');
Route::post('/task/update-status', [TaskController::class, 'updateStatus'])->middleware('role:admin');
Route::get('/task/dates', [TaskController::class, 'getDates']);
Route::get('/task/by-date', [TaskController::class, 'getByDate']);
Route::delete('/task/delete/{id}', [TaskController::class, 'destroy'])->middleware('role:admin');

// Task Notifications
Route::get('/task-notifications', [TaskNotificationController::class, 'index']);
Route::get('/task-notifications/unread-count', [TaskNotificationController::class, 'unreadCount']);
Route::post('/task-notifications/mark-all-read', [TaskNotificationController::class, 'markAllRead']);
Route::post('/task-notifications/{id}/mark-read', [TaskNotificationController::class, 'markRead']);

/*
|--------------------------------------------------------------------------
| LEADERS DIRECTORY
|--------------------------------------------------------------------------
*/
Route::get('/leaders-directory', [LeadersDirectoryController::class, 'index'])->name('leaders-directory.index');
Route::post('/leaders-directory/organization', [LeadersDirectoryController::class, 'storeOrganization'])->middleware('role:admin')->name('leaders-directory.store-org');
Route::delete('/leaders-directory/organization/{id}', [LeadersDirectoryController::class, 'destroyOrganization'])->middleware('role:admin')->name('leaders-directory.destroy-org');
Route::delete('/leaders-directory/member/{id}', [LeadersDirectoryController::class, 'destroyMember'])->middleware('role:admin')->name('leaders-directory.destroy-member');
Route::put('/leaders-directory/member/{id}', [LeadersDirectoryController::class, 'updateMember'])->middleware('role:admin')->name('leaders-directory.update-member');
Route::get('/leaders-directory/search', [LeadersDirectoryController::class, 'search'])->name('leaders-directory.search');

}); // End auth middleware group
