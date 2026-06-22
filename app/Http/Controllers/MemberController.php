<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Member;
use Illuminate\View\View;
use Carbon\Carbon;
use App\Models\Church;
use App\Models\ActivityLog;



class MemberController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->search;

        $members = Member::with('church')
        ->where('is_leader', false)
        ->when($search, function ($query, $search) {

            $query->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('middle_initial', 'like', "%{$search}%")
                ->orWhere('mobile', 'like', "%{$search}%");

        })->get();
    
        return view('members.index', compact('members'));
    }

    public function create(Request $request): View
    {
        $churches = Church::all();
        $isLeader = $request->has('leader');
        $organization = $request->get('organization', '');

        return view('members.create', compact('churches', 'isLeader', 'organization'));
    }

    public function store(Request $request): RedirectResponse
    {
    $input = $request->all();

    // SAVE CROPPED IMAGE
    if ($request->cropped_photo) {

        $image = $request->cropped_photo;
        $folderPath = public_path('uploads/');

        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }

        $image_parts = explode(";base64,", $image);
        $image_base64 = base64_decode($image_parts[1]);

        $fileName = uniqid() . '.png';
        file_put_contents($folderPath . $fileName, $image_base64);

        $input['photo'] = $fileName;
        }

        // MEMBERSHIP STATUS
        $input['membership_status'] = $request->membership_status;


        // ================= BAPTIZED =================
        if ($request->membership_status === 'baptized') {

            $input['baptism_date'] = $request->baptism_date;

            $input['baptism_place'] = $request->baptism_place;

            $input['officiating_minister'] = $request->officiating_minister;
        }


        // ================= DEDICATED =================
        elseif ($request->membership_status === 'dedicated') {

        // SAVE DEDICATION INTO BAPTISM COLUMNS

            $input['baptism_date'] = $request->dedication_date;

            $input['baptism_place'] = $request->dedication_place;

            $input['officiating_minister'] = $request->dedication_minister;
        }


        // ================= N/A =================
        else {

            $input['baptism_date'] = null;

            $input['baptism_place'] = 'N/A';

            $input['officiating_minister'] = 'N/A';
        }

        Member::create($input);

        ActivityLog::log('Members', 'Created', 'Added new member: ' . $input['first_name'] . ' ' . $input['last_name']);

        // Redirect based on context
        if ($request->is_leader) {
            return redirect('/leaders-directory')->with('flash_message', 'Officer Added Successfully');
        }

        return redirect('members')->with('flash_message', 'Member Added');
    }

    public function show(string $id): View
    {
        $members = Member::with('church')->find($id);
        return view('members.show')->with('members',$members);
    }

    public function edit(string $id): View
    {
        $members = Member::find($id);

    $churches = Church::all();

    return view('members.edit', compact('members', 'churches'));

    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $member = Member::find($id);

        $input = $request->all();

        // UPDATE IMAGE
        if ($request->cropped_photo) {

            $image = $request->cropped_photo;
            $folderPath = public_path('uploads/');

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $image_parts = explode(";base64,", $image);
            $image_base64 = base64_decode($image_parts[1]);

            $fileName = uniqid() . '.png';

            file_put_contents($folderPath . $fileName, $image_base64);

            $input['photo'] = $fileName;
        }

        // ================= BAPTIZED =================
        if ($request->membership_status === 'baptized') {

            $input['baptism_date'] = $request->baptism_date;

            $input['baptism_place'] = $request->baptism_place;

            $input['officiating_minister'] = $request->officiating_minister;
        }

        // ================= DEDICATED =================
        elseif ($request->membership_status === 'dedicated') {

            $input['baptism_date'] = $request->dedication_date;

            $input['baptism_place'] = $request->dedication_place;

            $input['officiating_minister'] = $request->dedication_minister;
        }

        // ================= N/A =================
        else {

            $input['baptism_date'] = null;

            $input['baptism_place'] = 'N/A';

            $input['officiating_minister'] = 'N/A';
        }

        // Preserve existing address values if not provided (Livewire sends null when unchanged)
        $addressFields = ['region', 'province', 'city', 'barangay', 'street'];
        foreach ($addressFields as $field) {
            if (!isset($input[$field]) || $input[$field] === null || $input[$field] === '') {
                unset($input[$field]);
            }
        }

        $member->update($input);

        ActivityLog::log('Members', 'Updated', 'Updated member: ' . $member->first_name . ' ' . $member->last_name, $member->id);

        return redirect('members')->with('flash_message', 'Member Updated!');
    }

    public function destroy(string $id): RedirectResponse
    {
        $member = Member::findOrFail($id);
        ActivityLog::log('Members', 'Deleted', 'Deleted member: ' . $member->full_name, $id);
        $member->delete();
        return redirect('members')->with('flash_message', 'Member Deleted!');
    }

    public function showJson($id)
    {
        $member = Member::findOrFail($id);
    
        return response()->json([
            'id' => $member->id,
            'name' => $member->first_name . ' ' . $member->last_name,
    
            'age' => Carbon::parse($member->birthdate)->age,
    
            'birthdate' => $member->birthdate,
            'gender' => $member->gender,
            'mobile' => $member->mobile,
    
            'cluster' => $member->church->cluster ?? 'N/A',
    
            'address' => collect([
                $member->street,
                $member->barangay,
                $member->city,
                $member->province,
                $member->region,
            ])->filter()->implode(', '),
    
            'photo' => $member->photo
                ? asset('uploads/' . $member->photo)
                : '',
        ]);
    }
}