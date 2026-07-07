<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebsiteController;

/*
|--------------------------------------------------------------------------
| Public Church Website Routes (Static)
|--------------------------------------------------------------------------
| Completely isolated from CMS routes.
*/

Route::get('/', [WebsiteController::class, 'home']);

Route::prefix('website')->name('website.')->group(function () {
    Route::view('/', 'website.home')->name('home');
    Route::view('/about', 'website.about')->name('about');
    Route::get('/pastors-message', [WebsiteController::class, 'pastorsMessage'])->name('pastors-message');
    Route::get('/ministries', [WebsiteController::class, 'ministries'])->name('ministries');
    Route::get('/events', [WebsiteController::class, 'events'])->name('events');
    Route::get('/announcements', [WebsiteController::class, 'announcements'])->name('announcements');
    Route::get('/gallery', [WebsiteController::class, 'gallery'])->name('gallery');
    Route::get('/gallery/{album}', [WebsiteController::class, 'galleryAlbum'])->name('gallery.album');
    Route::view('/contact', 'website.contact')->name('contact');
});
