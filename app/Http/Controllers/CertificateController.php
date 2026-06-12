<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\ActivityLog;

class CertificateController extends Controller
{
    // INDEX
    public function index()
    {
        $certificates = Certificate::latest()->get();

        return view('certificates.index', compact('certificates'));
    }

    // CREATE
    public function create()
    {
        return view('certificates.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'certificate_name' => 'required'
        ]);

        Certificate::create([
            'certificate_name' => $request->certificate_name
        ]);

        ActivityLog::log('Templates', 'Created', 'Created template: ' . $request->certificate_name);

        return redirect()->route('certificates.index')
                         ->with('success', 'Certificate added successfully.');
    }

    // EDIT
    public function edit($id)
    {
        $certificate = Certificate::findOrFail($id);

        return view('certificates.edit', compact('certificate'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $request->validate([
            'certificate_name' => 'required'
        ]);

        $certificate = Certificate::findOrFail($id);

        $certificate->update([
            'certificate_name' => $request->certificate_name
        ]);

        ActivityLog::log('Templates', 'Updated', 'Updated template: ' . $request->certificate_name, $certificate->id);

        return redirect()->route('certificates.index')
                         ->with('success', 'Certificate updated.');
    }

    // SEARCH MEMBER
    public function search(Request $request)
    {
        $search = $request->search;

        $members = Member::with('church')
            ->where('first_name', 'LIKE', "%{$search}%")
            ->orWhere('last_name', 'LIKE', "%{$search}%")
            ->limit(10)
            ->get();

        return response()->json(

            $members->map(function($member){

                return [

                    'id' => $member->id,

                    'first_name' => $member->first_name,

                    'middle_initial' => $member->middle_initial,

                    'last_name' => $member->last_name,

                    'photo' => $member->photo,

                    // 👇 CHURCH NAME
                    'church_name' => $member->church->church_name ?? 'No Church',

                ];

            })

        );
    }

    public function baptismForm($id)
    {
    $member = Member::findOrFail($id);

    return view('certificates.baptism', compact('member'));
    }

    public function printBaptism(Request $request)
    {
        $member = Member::with('church')
            ->findOrFail($request->member_id);

        return view('certificates.baptism_print', compact(
            'member',
            'request'
        ));
    }

    // SHOW MEMBER INFO
    public function show($id)
    {
        $member = Member::findOrFail($id);

        return view('certificates.baptism-print', compact('member'));
    }

    // DELETE
    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);

        ActivityLog::log('Templates', 'Deleted', 'Deleted template: ' . $certificate->certificate_name, $certificate->id);

        $certificate->delete();

        return redirect()->route('certificates.index')
                         ->with('success', 'Certificate deleted.');
    }
}