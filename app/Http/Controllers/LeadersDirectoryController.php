<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Member;
use Illuminate\Http\Request;

class LeadersDirectoryController extends Controller
{
    public function index()
    {
        $organizations = Organization::all();

        // Load leaders grouped by organization
        $orgMembers = [];
        foreach ($organizations as $org) {
            $orgMembers[$org->id] = Member::where('is_leader', true)
                ->where('organization', $org->name)
                ->get();
        }

        return view('leaders-directory.index', compact('organizations', 'orgMembers'));
    }

    public function storeOrganization(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);

        Organization::create([
            'name' => $request->name,
            'icon' => 'mdi-account-group',
            'color' => '#2449d8',
            'bg_color' => '#eef4ff',
        ]);

        return redirect('/leaders-directory')->with('flash_message', 'Organization Added');
    }

    public function destroyMember($id)
    {
        $member = Member::findOrFail($id);
        $member->delete();

        return response()->json(['success' => true]);
    }

    public function destroyOrganization($id)
    {
        $org = Organization::findOrFail($id);

        // Also remove all officers assigned to this organization
        Member::where('is_leader', true)->where('organization', $org->name)->delete();

        $org->delete();

        return response()->json(['success' => true, 'message' => 'Organization deleted']);
    }

    public function updateMember(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $member->update([
            'first_name' => explode(' ', $request->name)[0] ?? $request->name,
            'last_name' => explode(' ', $request->name, 2)[1] ?? '',
            'position' => $request->position,
            'organization' => $request->organization,
        ]);

        return response()->json(['success' => true]);
    }
}
