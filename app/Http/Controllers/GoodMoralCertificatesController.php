<?php

namespace App\Http\Controllers;

use App\Models\GoodMoralCertificates;
use App\Models\Member;
use App\Models\CertificateLog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class GoodMoralCertificatesController extends Controller
{
    /**
     * Search members for good moral certificate
     */
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

    /**
     * Show good moral certificate form for a member
     */
    public function form($id)
    {
        $member = Member::with('church')->findOrFail($id);

        return view('certificates.goodmoralcert', compact('member'));
    }

    /**
     * Generate and print good moral certificate
     */
    public function print(Request $request)
    {
        $member = Member::with('church')->findOrFail($request->member_id);

        // Auto-generate certificate number
        $year = now()->year;
        $count = GoodMoralCertificates::whereYear('created_at', $year)->count() + 1;
        $certificateNo = 'GM-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Build full name with honorific
        $honorific = $member->gender === 'Female' ? 'MS.' : 'MR.';
        $fullName = trim(
            $honorific . ' ' .
            strtoupper($member->first_name) . ' ' .
            ($member->middle_initial ? strtoupper($member->middle_initial) . ' ' : '') .
            strtoupper($member->last_name) .
            ($member->suffix ? ' ' . strtoupper($member->suffix) : '')
        );

        // Display name for third paragraph (Mr./Ms. LastName)
        $displayLastName = $member->gender === 'Female' ? 'Ms.' : 'Mr.';
        $shortName = $displayLastName . ' ' . $member->last_name;

        // Save record
        GoodMoralCertificates::create([
            'certificate_no' => $certificateNo,
            'member_id' => $member->id,
            'full_name' => $fullName,
            'church_name' => $request->church_name ?? $member->church->church_name ?? '',
            'church_location' => $request->church_location ?? '',
            'purpose' => $request->purpose ?? '',
            'issued_date' => $request->issued_date ?? now()->toDateString(),
            'elder_name' => $request->elder_name ?? '',
            'issued_by' => auth()->user()->name ?? 'Admin',
        ]);

        // Create CertificateLog entry
        CertificateLog::create([
            'member_id' => $member->id,
            'certificate_type' => 'Good Moral',
            'certificate_number' => $certificateNo,
            'printed_by' => auth()->user()->name ?? 'Admin',
            'printed_at' => now(),
        ]);

        // Activity Log
        ActivityLog::log(
            'Certificates',
            'Generated',
            'Generated good moral certificate ' . $certificateNo . ' for ' . $member->first_name . ' ' . $member->last_name,
            $member->id
        );

        $pronoun = $member->gender === 'Female' ? 'she' : 'he';

        return view('certificates.goodmoralcert_print', [
            'member' => $member,
            'certificateNo' => $certificateNo,
            'fullName' => $fullName,
            'shortName' => $shortName,
            'churchLocation' => $request->church_location ?? 'Buhangin, Davao City',
            'purpose' => $request->purpose ?? '',
            'issuedDate' => $request->issued_date ?? now()->toDateString(),
            'elderName' => $request->elder_name ?? '',
            'pronoun' => $pronoun,
        ]);
    }
}
