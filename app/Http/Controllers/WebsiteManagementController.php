<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PastorMessage;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\Ministry;
use App\Models\GalleryAlbum;
use App\Models\GalleryPhoto;
use App\Models\ChurchInformation;
use App\Models\ChurchFeaturedLeader;
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
                'status' => GalleryPhoto::count() > 0 ? 'published' : 'not-published',
                'updated_at' => GalleryAlbum::latest('updated_at')->first()?->updated_at,
                'albums_count' => GalleryAlbum::count(),
                'photos_count' => GalleryPhoto::count(),
                'latest_photos' => GalleryPhoto::latest()->take(4)->pluck('filename')->toArray(),
                'extra_count' => max(0, GalleryPhoto::count() - 4),
            ],
            'about' => [
                'status' => ChurchInformation::first() ? 'published' : 'not-published',
                'updated_at' => ChurchInformation::first()?->updated_at,
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
            'icon' => $e->icon ?? 'mdi-calendar-star',
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
            'icon' => 'required|string|max:100',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'event_time' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $event = Event::create($request->only('title', 'icon', 'description', 'event_date', 'event_time', 'location', 'is_published'));

        ActivityLog::log('Website Management', 'Created', 'Created event "' . $event->title . '"', $event->id);

        return response()->json(['success' => true, 'event' => $event]);
    }

    public function updateEvent(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:100',
            'description' => 'nullable|string',
            'event_date' => 'nullable|date',
            'event_time' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $event = Event::findOrFail($id);
        $event->update($request->only('title', 'icon', 'description', 'event_date', 'event_time', 'location', 'is_published'));

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
            'announcement_date' => $a->announcement_date?->format('Y-m-d'),
            'location' => $a->location,
            'is_published' => $a->is_published,
            'updated_at' => $a->updated_at->format('M d, Y'),
        ]));
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'announcement_date' => 'required|date',
            'location' => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);

        $announcement = Announcement::create($request->only('title', 'description', 'announcement_date', 'location', 'is_published'));

        ActivityLog::log('Website Management', 'Created', 'Created announcement "' . $announcement->title . '"', $announcement->id);

        return response()->json(['success' => true, 'announcement' => $announcement]);
    }

    public function updateAnnouncement(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'announcement_date' => 'required|date',
            'location' => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);

        $announcement = Announcement::findOrFail($id);
        $announcement->update($request->only('title', 'description', 'announcement_date', 'location', 'is_published'));

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

    public function getAlbums(Request $request)
    {
        $query = GalleryAlbum::withCount('photos')->latest('updated_at');

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        return response()->json($query->get()->map(fn($a) => [
            'id' => $a->id,
            'title' => $a->title,
            'description' => $a->description,
            'icon' => $a->icon,
            'gradient_from' => $a->gradient_from,
            'gradient_to' => $a->gradient_to,
            'is_published' => $a->is_published,
            'photos_count' => $a->photos_count,
            'updated_at' => $a->updated_at->format('M d, Y'),
        ]));
    }

    public function storeAlbum(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'gradient_from' => 'nullable|string|max:20',
            'gradient_to' => 'nullable|string|max:20',
            'is_published' => 'required',
        ]);

        try {
            $album = GalleryAlbum::create([
                'title' => $request->title,
                'description' => $request->description,
                'icon' => $request->icon ?? 'mdi-image-multiple',
                'gradient_from' => $request->gradient_from ?? '#667eea',
                'gradient_to' => $request->gradient_to ?? '#764ba2',
                'is_published' => filter_var($request->is_published, FILTER_VALIDATE_BOOLEAN),
            ]);

            ActivityLog::log('Website Management', 'Created', 'Created gallery album "' . $album->title . '"', $album->id);

            return response()->json([
                'success' => true,
                'album' => [
                    'id' => $album->id,
                    'title' => $album->title,
                    'description' => $album->description,
                    'icon' => $album->icon,
                    'gradient_from' => $album->gradient_from,
                    'gradient_to' => $album->gradient_to,
                    'is_published' => $album->is_published,
                    'photos_count' => 0,
                    'updated_at' => $album->updated_at->format('M d, Y'),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function updateAlbum(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:100',
            'gradient_from' => 'nullable|string|max:20',
            'gradient_to' => 'nullable|string|max:20',
            'is_published' => 'required|in:0,1,true,false',
        ]);

        $album = GalleryAlbum::findOrFail($id);
        $album->update([
            'title' => $request->title,
            'description' => $request->description,
            'icon' => $request->icon ?? $album->icon,
            'gradient_from' => $request->gradient_from ?? $album->gradient_from,
            'gradient_to' => $request->gradient_to ?? $album->gradient_to,
            'is_published' => filter_var($request->is_published, FILTER_VALIDATE_BOOLEAN),
        ]);

        ActivityLog::log('Website Management', 'Updated', 'Updated gallery album "' . $album->title . '"', $album->id);

        return response()->json([
            'success' => true,
            'album' => [
                'id' => $album->id,
                'title' => $album->title,
                'description' => $album->description,
                'icon' => $album->icon,
                'gradient_from' => $album->gradient_from,
                'gradient_to' => $album->gradient_to,
                'is_published' => $album->is_published,
                'photos_count' => $album->photos()->count(),
                'updated_at' => $album->updated_at->format('M d, Y'),
            ]
        ]);
    }

    public function destroyAlbum($id)
    {
        $album = GalleryAlbum::findOrFail($id);
        $title = $album->title;
        $album->delete();

        ActivityLog::log('Website Management', 'Deleted', 'Deleted gallery album "' . $title . '"');

        return response()->json(['success' => true]);
    }

    public function showAlbum($id)
    {
        $album = GalleryAlbum::withCount('photos')->findOrFail($id);
        return view('website-management.gallery-album', compact('album'));
    }

    public function getPhotos($albumId)
    {
        $photos = GalleryPhoto::where('album_id', $albumId)
            ->latest()
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'filename' => $p->filename,
                'caption' => $p->caption,
                'url' => asset('uploads/gallery/' . $p->filename),
                'created_at' => $p->created_at->format('M d, Y'),
            ]);

        return response()->json($photos);
    }

    public function uploadPhotos(Request $request, $albumId)
    {
        $request->validate([
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|max:10240',
        ]);

        $album = GalleryAlbum::findOrFail($albumId);
        $uploaded = [];

        $dir = public_path('uploads/gallery');
        if (!file_exists($dir)) mkdir($dir, 0777, true);

        foreach ($request->file('photos') as $file) {
            $filename = 'photo_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);

            $photo = GalleryPhoto::create([
                'album_id' => $album->id,
                'filename' => $filename,
                'caption' => null,
            ]);

            $uploaded[] = [
                'id' => $photo->id,
                'filename' => $photo->filename,
                'caption' => $photo->caption,
                'url' => asset('uploads/gallery/' . $photo->filename),
                'created_at' => $photo->created_at->format('M d, Y'),
            ];
        }

        $album->touch();
        ActivityLog::log('Website Management', 'Uploaded', 'Uploaded ' . count($uploaded) . ' photo(s) to "' . $album->title . '"', $album->id);

        return response()->json(['success' => true, 'photos' => $uploaded, 'count' => count($uploaded)]);
    }

    public function deletePhoto($photoId)
    {
        $photo = GalleryPhoto::findOrFail($photoId);
        $filepath = public_path('uploads/gallery/' . $photo->filename);
        if (file_exists($filepath)) unlink($filepath);

        $album = $photo->album;
        $photo->delete();
        $album->touch();

        ActivityLog::log('Website Management', 'Deleted', 'Deleted photo from "' . $album->title . '"', $album->id);

        return response()->json(['success' => true]);
    }

    // ========== ABOUT US ==========

    public function about()
    {
        $churchInfo = ChurchInformation::first();
        $leaders = ChurchFeaturedLeader::with('member')->orderBy('sort_order')->get();

        return view('website-management.about', compact('churchInfo', 'leaders'));
    }

    public function saveAbout(Request $request)
    {
        $request->validate([
            'church_history' => 'nullable|string',
            'mission' => 'nullable|string',
            'vision' => 'nullable|string',
            'core_values' => 'nullable|string',
            'church_image' => 'nullable|image|max:5120',
        ]);

        $churchInfo = ChurchInformation::first() ?: new ChurchInformation();

        $churchInfo->church_history = $request->church_history;
        $churchInfo->mission = $request->mission;
        $churchInfo->vision = $request->vision;
        $churchInfo->core_values = $request->core_values;

        if ($request->hasFile('church_image')) {
            $dir = public_path('uploads/about');
            if (!file_exists($dir)) mkdir($dir, 0777, true);
            $filename = 'church_' . uniqid() . '.' . $request->file('church_image')->getClientOriginalExtension();
            $request->file('church_image')->move($dir, $filename);
            $churchInfo->church_image = $filename;
        }

        $churchInfo->save();

        ActivityLog::log('Website Management', 'Updated', 'Updated About Us church information');

        return response()->json(['success' => true, 'churchInfo' => $churchInfo]);
    }

    public function getAboutData()
    {
        $churchInfo = ChurchInformation::first();
        $leaders = ChurchFeaturedLeader::with('member.church')->orderBy('sort_order')->get()->map(fn($l) => [
            'id' => $l->id,
            'member_id' => $l->member_id,
            'name' => $l->member?->full_name ?? '—',
            'organization' => $l->member?->organization ?? '',
            'position' => $l->member?->position ?? '',
            'church' => $l->member?->church?->church_name ?? '',
            'photo' => $l->member?->photo,
        ]);

        return response()->json([
            'churchInfo' => $churchInfo,
            'leaders' => $leaders,
        ]);
    }

    public function getAvailableLeaders(Request $request)
    {
        $query = Member::where('is_leader', true);

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%")
                  ->orWhere('organization', 'like', "%{$search}%");
            });
        }

        $members = $query->orderBy('last_name')->get()->map(fn($m) => [
            'id' => $m->id,
            'full_name' => $m->full_name,
            'position' => $m->position,
            'organization' => $m->organization,
            'church' => $m->church?->church_name ?? '',
            'photo' => $m->photo,
        ]);

        return response()->json($members);
    }

    public function saveLeaders(Request $request)
    {
        $request->validate([
            'member_ids' => 'required|array',
            'member_ids.*' => 'exists:members,id',
        ]);

        // Sync leaders - remove old, insert new
        ChurchFeaturedLeader::query()->delete();

        foreach ($request->member_ids as $index => $memberId) {
            ChurchFeaturedLeader::create([
                'member_id' => $memberId,
                'sort_order' => $index,
            ]);
        }

        ActivityLog::log('Website Management', 'Updated', 'Updated About Us featured leaders');

        return response()->json(['success' => true]);
    }

    public function removeLeader($id)
    {
        ChurchFeaturedLeader::findOrFail($id)->delete();

        return response()->json(['success' => true]);
    }
}
