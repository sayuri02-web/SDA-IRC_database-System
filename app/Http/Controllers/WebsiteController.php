<?php

namespace App\Http\Controllers;

use App\Models\PastorMessage;
use App\Models\Event;
use App\Models\Announcement;
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
}
