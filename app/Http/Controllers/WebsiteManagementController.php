<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PastorMessage;
use App\Models\Event;
use App\Models\Announcement;
use Illuminate\Http\Request;

class WebsiteManagementController extends Controller
{
    public function index()
    {
        $pastorMessage = PastorMessage::latest()->first();

        $latestEvent = Event::latest('updated_at')->first();
        $latestAnnouncement = Announcement::latest('updated_at')->first();
        $latestEA = $latestEvent && $latestAnnouncement
            ? ($latestEvent->updated_at > $latestAnnouncement->updated_at ? $latestEvent : $latestAnnouncement)
            : ($latestEvent ?? $latestAnnouncement);

        $cardStatuses = [
            'pastor' => [
                'status' => $pastorMessage ? 'published' : 'not-published',
                'updated_at' => $pastorMessage?->updated_at,
            ],
            'events' => [
                'status' => ($latestEvent || $latestAnnouncement) ? 'published' : 'not-published',
                'updated_at' => $latestEA?->updated_at,
                'events_count' => Event::count(),
                'announcements_count' => Announcement::count(),
            ],
            'ministries' => [
                'status' => 'not-published',
                'updated_at' => null,
            ],
            'gallery' => [
                'status' => 'not-published',
                'updated_at' => null,
            ],
        ];

        return view('website-management.index', compact('cardStatuses'));
    }

    public function pastorMessage()
    {
        $pastorMessage = PastorMessage::with('member')->latest()->first();
        return view('website-management.pastor-message', compact('pastorMessage'));
    }

    public function savePastorMessage(Request $request)
    {
        $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'title' => 'nullable|string|max:500',
            'content' => 'nullable|string',
        ]);

        $pastorMessage = PastorMessage::first();

        if ($pastorMessage) {
            $pastorMessage->update([
                'member_id' => $request->member_id ?: $pastorMessage->member_id,
                'title' => $request->title,
                'content' => $request->content,
                'is_published' => true,
            ]);
        } else {
            $pastorMessage = PastorMessage::create([
                'member_id' => $request->member_id,
                'title' => $request->title,
                'content' => $request->content,
                'is_published' => true,
            ]);
        }

        return response()->json(['success' => true, 'updated_at' => $pastorMessage->updated_at->format('F d, Y h:i A')]);
    }

    public function pastors()
    {
        $pastors = Member::where('is_leader', true)
            ->where('position', 'LIKE', '%Pastor%')
            ->select('id', 'photo', 'first_name', 'middle_initial', 'last_name', 'suffix', 'position', 'organization')
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'photo' => $m->photo,
                'full_name' => $m->full_name,
                'position' => $m->position,
                'organization' => $m->organization,
                'status' => 'Active',
            ]);

        return response()->json($pastors);
    }

    // ========== EVENTS & ANNOUNCEMENTS PAGE ==========

    public function eventsAnnouncements()
    {
        return view('website-management.events-announcements');
    }

    // ========== EVENTS CRUD ==========

    public function getEvents(Request $request)
    {
        $query = Event::query()->latest();

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->get()->map(fn($e) => [
            'id' => $e->id,
            'title' => $e->title,
            'description' => $e->description,
            'event_date' => $e->event_date?->format('Y-m-d'),
            'event_time' => $e->event_time,
            'location' => $e->location,
            'is_published' => $e->is_published,
            'updated_at' => $e->updated_at->format('M d, Y'),
        ]));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'event_time' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $event = Event::create($request->only('title', 'description', 'event_date', 'event_time', 'location', 'is_published'));

        return response()->json(['success' => true, 'event' => $event]);
    }

    public function updateEvent(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'event_time' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->only('title', 'description', 'event_date', 'event_time', 'location', 'is_published'));

        return response()->json(['success' => true, 'event' => $event]);
    }

    public function destroyEvent($id)
    {
        Event::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function toggleEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->update(['is_published' => !$event->is_published]);
        return response()->json(['success' => true, 'is_published' => $event->is_published]);
    }

    // ========== ANNOUNCEMENTS CRUD ==========

    public function getAnnouncements(Request $request)
    {
        $query = Announcement::query()->latest();

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->get()->map(fn($a) => [
            'id' => $a->id,
            'title' => $a->title,
            'description' => $a->description,
            'is_published' => $a->is_published,
            'updated_at' => $a->updated_at->format('M d, Y'),
        ]));
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $announcement = Announcement::create($request->only('title', 'description', 'is_published'));

        return response()->json(['success' => true, 'announcement' => $announcement]);
    }

    public function updateAnnouncement(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        $announcement = Announcement::findOrFail($id);
        $announcement->update($request->only('title', 'description', 'is_published'));

        return response()->json(['success' => true, 'announcement' => $announcement]);
    }

    public function destroyAnnouncement($id)
    {
        Announcement::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function toggleAnnouncement($id)
    {
        $a = Announcement::findOrFail($id);
        $a->update(['is_published' => !$a->is_published]);
        return response()->json(['success' => true, 'is_published' => $a->is_published]);
    }

    // ========== OTHER MODULES ==========

    public function ministries()
    {
        return view('website-management.ministries');
    }

    public function gallery()
    {
        return view('website-management.gallery');
    }
}
