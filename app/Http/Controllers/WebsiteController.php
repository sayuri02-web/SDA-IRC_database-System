<?php

namespace App\Http\Controllers;

use App\Models\PastorMessage;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\Ministry;
use App\Models\GalleryAlbum;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function home()
    {
        return view('website.home');
    }

    public function pastorsMessage()
    {
        $pastorMessage = PastorMessage::with('member')
            ->where('is_published', true)
            ->latest()
            ->first();

        return view('website.pastors-message', compact('pastorMessage'));
    }

    public function events()
    {
        $events = Event::published()
            ->orderBy('event_date', 'asc')
            ->get();

        return view('website.events', compact('events'));
    }

    public function announcements()
    {
        $announcements = Announcement::published()
            ->latest()
            ->get();

        return view('website.announcements', compact('announcements'));
    }

    public function ministries()
    {
        $ministries = Ministry::published()
            ->orderBy('name')
            ->get();

        return view('website.ministries', compact('ministries'));
    }

    public function gallery()
    {
        $albums = GalleryAlbum::published()
            ->withCount('photos')
            ->latest()
            ->get();

        return view('website.gallery', compact('albums'));
    }

    public function galleryAlbum($id)
    {
        $album = GalleryAlbum::published()
            ->withCount('photos')
            ->findOrFail($id);

        $photos = $album->photos()->latest()->get();

        return view('website.gallery-album', compact('album', 'photos'));
    }
}
