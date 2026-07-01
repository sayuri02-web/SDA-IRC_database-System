<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\PastorMessage;
use Illuminate\Http\Request;

class WebsiteManagementController extends Controller
{
    public function index()
    {
        $pastorMessage = PastorMessage::latest()->first();

        $cardStatuses = [
            'pastor' => [
                'status' => $pastorMessage ? 'published' : 'not-published',
                'updated_at' => $pastorMessage?->updated_at,
            ],
            'events' => [
                'status' => 'not-published',
                'updated_at' => null,
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

        // Update or create the single pastor message record
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

        return redirect()->route('website-management.pastor-message')
            ->with('success', 'Pastor\'s Message saved successfully.');
    }

    //FETCH PASTORS FROM DATABASE
    public function pastors()
    {
        $pastors = Member::where('is_leader', true)
            ->where('position', 'LIKE', '%Pastor%')
            ->select(
                'id',
                'photo',
                'first_name',
                'middle_initial',
                'last_name',
                'suffix',
                'position',
                'organization'
            )
            ->get()
            ->map(function ($member) {

                return [

                    'id' => $member->id,

                    'photo' => $member->photo,

                    'full_name' => trim(
                        $member->first_name . ' ' .
                        ($member->middle_initial ? $member->middle_initial . '. ' : '') .
                        $member->last_name .
                        ($member->suffix ? ' ' . $member->suffix : '')
                    ),

                    'position' => $member->position,
                    'organization' => $member->organization,

                    'status' => 'Active'
                ];
            });

        return response()->json($pastors);
    }

    public function events()
    {
        return view('website-management.events');
    }

    public function ministries()
    {
        return view('website-management.ministries');
    }

    public function gallery()
    {
        return view('website-management.gallery');
    }
}