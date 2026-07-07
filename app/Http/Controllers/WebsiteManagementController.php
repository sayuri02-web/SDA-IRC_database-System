<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PastorMessage;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\Ministry;
use App\Models\ActivityLog;
use App\Http\Requests\StoreMinistryRequest;
use App\Http\Requests\UpdateMinistryRequest;
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
                'status' => Ministry::count() > 0 ? 'published' : 'not-published',
                'updated_at' => Ministry::latest('updated_at')->first()?->updated_at,
                'count' => Ministry::count(),
            ],
            'gallery' => [
                'status' => 'not-published',
                'updated_at' => null,
            ],
        ];

        return view('website-management.index', compact('cardStatuses'));
    }

    // ========== PASTOR'S MESSAGE ==========

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
        $isNew = !$pastorMessage;

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

        ActivityLog::log(
            'Website Management',
            $isNew ? 'Created' : 'Updated',
            ($isNew ? 'Created' : 'Updated') . ' Pastor\'s Message' . ($request->title ? ': "' . $request->title . '"' : ''),
            $pastorMessage->id
        );

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

        ActivityLog::log('Website Management', 'Created', 'Created event "' . $event->title . '"', $event->id);

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

        ActivityLog::log('Website Management', 'Updated', 'Updated event "' . $event->title . '"', $event->id);

        return response()->json(['success' => true, 'event' => $event]);
    }

    public function destroyEvent($id)
    {
        $event = Event::findOrFail($id);
        $title = $event->title;
        $event->delete();

        ActivityLog::log('Website Management', 'Deleted', 'Deleted event "' . $title . '"');

        return response()->json(['success' => true]);
    }

    public function toggleEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->update(['is_published' => !$event->is_published]);

        $action = $event->is_published ? 'Published' : 'Unpublished';
        ActivityLog::log('Website Management', $action, $action . ' event "' . $event->title . '"', $event->id);

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

        ActivityLog::log('Website Management', 'Created', 'Created announcement "' . $announcement->title . '"', $announcement->id);

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

        ActivityLog::log('Website Management', 'Updated', 'Updated announcement "' . $announcement->title . '"', $announcement->id);

        return response()->json(['success' => true, 'announcement' => $announcement]);
    }

    public function destroyAnnouncement($id)
    {
        $announcement = Announcement::findOrFail($id);
        $title = $announcement->title;
        $announcement->delete();

        ActivityLog::log('Website Management', 'Deleted', 'Deleted announcement "' . $title . '"');

        return response()->json(['success' => true]);
    }

    public function toggleAnnouncement($id)
    {
        $a = Announcement::findOrFail($id);
        $a->update(['is_published' => !$a->is_published]);

        $action = $a->is_published ? 'Published' : 'Unpublished';
        ActivityLog::log('Website Management', $action, $action . ' announcement "' . $a->title . '"', $a->id);

        return response()->json(['success' => true, 'is_published' => $a->is_published]);
    }

    // ========== MINISTRIES CRUD ==========

    public function getMinistries(Request $request)
    {
        $query = Ministry::query()->latest();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->get()->map(fn($m) => [
            'id' => $m->id,
            'name' => $m->name,
            'description' => $m->description,
            'icon' => $m->icon,
            'is_published' => $m->is_published,
            'updated_at' => $m->updated_at->format('M d, Y'),
        ]));
    }

    public function storeMinistry(StoreMinistryRequest $request)
    {
        $ministry = Ministry::create($request->validated());

        ActivityLog::log('Website Management', 'Created', 'Created ministry "' . $ministry->name . '"', $ministry->id);

        return response()->json(['success' => true, 'ministry' => $ministry]);
    }

    public function updateMinistry(UpdateMinistryRequest $request, $id)
    {
        $ministry = Ministry::findOrFail($id);
        $ministry->update($request->validated());

        ActivityLog::log('Website Management', 'Updated', 'Updated ministry "' . $ministry->name . '"', $ministry->id);

        return response()->json(['success' => true, 'ministry' => $ministry]);
    }

    public function destroyMinistry($id)
    {
        $ministry = Ministry::findOrFail($id);
        $name = $ministry->name;
        $ministry->delete();

        ActivityLog::log('Website Management', 'Deleted', 'Deleted ministry "' . $name . '"');

        return response()->json(['success' => true]);
    }

    public function toggleMinistry($id)
    {
        $ministry = Ministry::findOrFail($id);
        $ministry->update(['is_published' => !$ministry->is_published]);

        $action = $ministry->is_published ? 'Published' : 'Unpublished';
        ActivityLog::log('Website Management', $action, $action . ' ministry "' . $ministry->name . '"', $ministry->id);

        return response()->json(['success' => true, 'is_published' => $ministry->is_published]);
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
