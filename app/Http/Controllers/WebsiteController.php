<?php

namespace App\Http\Controllers;

use App\Models\PastorMessage;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\Ministry;
use App\Models\Member;
use App\Models\Church;
use App\Models\CertificateLog;
use App\Models\ChurchInformation;
use App\Models\ChurchFeaturedLeader;
use App\Models\GalleryAlbum;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function home()
    {
        // God's Work in Numbers
        $stats = [
            'members' => Member::count(),
            'churches' => Church::count(),
            'certificates' => CertificateLog::count(),
            'upcoming_events' => Event::published()->where('event_date', '>=', now()->toDateString())->count(),
        ];

        // Upcoming Events (latest 3 published, same source as Events page)
        $events = Event::published()
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        // Latest Announcements (latest 3 published, nearest upcoming first)
        $announcements = Announcement::published()
            ->orderBy('announcement_date', 'asc')
            ->take(3)
            ->get();

        // Church Information (for Mission, Vision, Core Values)
        $churchInfo = ChurchInformation::first();

        return view('website.home', compact('stats', 'events', 'announcements', 'churchInfo'));
    }

    public function pastorsMessage()
    {
        $pastorMessage = PastorMessage::with('member')
            ->where('is_published', true)
            ->latest()
            ->first();

        return view('website.pastors-message', compact('pastorMessage'));
    }

    public function about()
    {
        $churchInfo = ChurchInformation::first();
        $leaders = ChurchFeaturedLeader::with('member.church')->orderBy('sort_order')->get();

        return view('website.about', compact('churchInfo', 'leaders'));
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
            ->orderBy('announcement_date', 'asc')
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
            ->with(['photos' => function ($q) {
                $q->latest()->take(4);
            }])
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
