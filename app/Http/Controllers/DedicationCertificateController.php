<?php

namespace App\Http\Controllers;

use App\Models\DedicationCertificate;
use App\Models\Member;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class DedicationCertificateController extends Controller
{
    // Search members for dedication certificate
    public function search(Request $request)
    {
        $search = $request->search;

        $members = Member::with('church')
            ->where(function ($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get();

        return response()->json($members->map(function ($member) {
            return [
                'id' => $member->id,
                'first_name' => $member->first_name,
                'middle_initial' => $member->middle_initial,
                'last_name' => $member->last_name,
                'photo' => $member->photo,
                'church_name' => $member->church->church_name ?? 'No Church',
            ];
        }));
    }

    // Show dedication form for a member
    public function form($id)
    {
        $member = Member::with('church')->findOrFail($id);

        return view('certificates.dedication', compact('member'));
    }

    // Print dedication certificate
    public function print(Request $request)
    {
        $member = Member::with('church')->findOrFail($request->member_id);

        // Generate certificate number
        $year = now()->year;
        $count = DedicationCertificate::whereYear('created_at', $year)->count() + 1;
        $certificateNo = 'DED-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Save record
        DedicationCertificate::create([
            'certificate_no' => $certificateNo,
            'member_id' => $member->id,
            'child_name' => trim($member->first_name . ' ' . ($member->middle_initial ? $member->middle_initial . ' ' : '') . $member->last_name),
            'birth_place' => $member->birthplace,
            'birth_date' => $member->birthdate,
            'father_name' => $member->father_name,
            'mother_name' => $member->mother_name,
            'dedication_date' => $request->dedication_date ?? $member->baptism_date,
            'church_name' => $request->church_name ?? $member->church->church_name ?? '',
            'church_location' => $request->church_location ?? '',
            'chairman_name' => $request->chairman ?? '',
            'minister_name' => $request->officiating_minister ?? $member->officiating_minister ?? '',
            'witnesses' => json_encode($request->witnesses ?? []),
            'issued_by' => auth()->user()->name ?? 'Admin',
        ]);

        // Log activity
        ActivityLog::log('Certificates', 'Generated', 'Generated dedication certificate ' . $certificateNo . ' for ' . $member->first_name . ' ' . $member->last_name, $member->id);

        // Filter out empty witness entries
        $witnesses = array_filter($request->witnesses ?? [], fn($w) => trim($w) !== '');

        return view('certificates.dedication_print', compact('member', 'request', 'certificateNo', 'witnesses'));
    }

    // List all dedication certificates
    public function history()
    {
        $certificates = DedicationCertificate::with('member')->latest()->get();

        return view('certificates.dedication_history', compact('certificates'));
    }
}
