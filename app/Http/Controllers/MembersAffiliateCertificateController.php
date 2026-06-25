<?php

namespace App\Http\Controllers;

use App\Models\MembersAffiliateCertificates;
use App\Models\Member;
use App\Models\CertificateLog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class MembersAffiliateCertificateController extends Controller
{
    /**
     * Search members for affiliate certificate
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
     * Show affiliate certificate form for a member
     */
    public function form($id)
    {
        $member = Member::with('church')->findOrFail($id);

        return view('certificates.membersaffiliate', compact('member'));
    }

    /**
     * Generate and print affiliate certificate
     */
    public function print(Request $request)
    {
        $member = Member::with('church')->findOrFail($request->member_id);

        // Auto-generate certificate number
        $year = now()->year;
        $count = MembersAffiliateCertificates::whereYear('created_at', $year)->count() + 1;
        $certificateNo = 'AFF-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $fullName = trim(
            $member->first_name . ' ' .
            ($member->middle_initial ? $member->middle_initial . '. ' : '') .
            $member->last_name .
            ($member->suffix ? ' ' . $member->suffix : '')
        );

        // Save record
        MembersAffiliateCertificates::create([
            'certificate_no' => $certificateNo,
            'member_id' => $member->id,
            'full_name' => $fullName,
            'church_name' => $request->church_name ?? $member->church->church_name ?? '',
            'church_location' => $request->church_location ?? '',
            'residence_cert_no' => $request->residence_cert_no ?? '',
            'residence_issued_at' => $request->residence_issued_at ?? '',
            'residence_issued_date' => $request->residence_issued_date ?? null,
            'done_date' => $request->done_date ?? now()->toDateString(),
            'elder_name' => $request->elder_name ?? '',
            'secretary_name' => $request->secretary_name ?? '',
            'chairman_name' => $request->chairman_name ?? '',
            'issued_by' => auth()->user()->name ?? 'Admin',
        ]);

        // Create CertificateLog entry
        CertificateLog::create([
            'member_id' => $member->id,
            'certificate_type' => 'Members Affiliate',
            'certificate_number' => $certificateNo,
            'printed_by' => auth()->user()->name ?? 'Admin',
            'printed_at' => now(),
        ]);

        // Activity Log
        ActivityLog::log(
            'Certificates',
            'Generated',
            'Generated members affiliate certificate ' . $certificateNo . ' for ' . $fullName,
            $member->id
        );

        return view('certificates.membersaffiliate_print', [
            'member' => $member,
            'certificateNo' => $certificateNo,
            'fullName' => $fullName,
            'churchLocation' => $request->church_location ?? 'Buhangin, Davao City',
            'residenceCertNo' => $request->residence_cert_no ?? '',
            'residenceIssuedAt' => $request->residence_issued_at ?? '',
            'residenceIssuedDate' => $request->residence_issued_date ?? null,
            'doneDate' => $request->done_date ?? now()->toDateString(),
            'elderName' => $request->elder_name ?? '',
            'secretaryName' => $request->secretary_name ?? '',
            'chairmanName' => $request->chairman_name ?? '',
        ]);
    }
}
