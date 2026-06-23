<?php

namespace App\Http\Controllers;

use App\Models\MembershipCertificate;
use App\Models\Member;
use App\Models\CertificateLog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class MembershipCertificateController extends Controller
{
    /**
     * Search members for membership certificate
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
     * Show membership certificate form for a member
     */
    public function form($id)
    {
        $member = Member::with('church')->findOrFail($id);

        return view('certificates.membership', compact('member'));
    }

    /**
     * Generate and print membership certificate
     */
    public function print(Request $request)
    {
        $member = Member::with('church')->findOrFail($request->member_id);

        // Auto-generate certificate number
        $year = now()->year;
        $count = MembershipCertificate::whereYear('created_at', $year)->count() + 1;
        $certificateNo = 'MEM-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $fullName = trim(
            ($member->gender === 'Female' ? 'SIS. ' : 'BRO. ') .
            strtoupper($member->first_name) . ' ' .
            ($member->middle_initial ? strtoupper($member->middle_initial) . '. ' : '') .
            strtoupper($member->last_name) .
            ($member->suffix ? ' ' . strtoupper($member->suffix) : '')
        );

        // Save record
        MembershipCertificate::create([
            'certificate_no' => $certificateNo,
            'member_id' => $member->id,
            'full_name' => $fullName,
            'church_name' => $request->church_name ?? $member->church->church_name ?? '',
            'church_location' => $request->church_location ?? '',
            'position' => $request->position ?? '',
            'issued_date' => $request->issued_date ?? now()->toDateString(),
            'secretary_name' => $request->secretary_name ?? '',
            'elder_name' => $request->elder_name ?? '',
            'issued_by' => auth()->user()->name ?? 'Admin',
        ]);

        // Create CertificateLog entry
        CertificateLog::create([
            'member_id' => $member->id,
            'certificate_type' => 'Membership',
            'certificate_number' => $certificateNo,
            'printed_by' => auth()->user()->name ?? 'Admin',
            'printed_at' => now(),
        ]);

        // Activity Log
        ActivityLog::log(
            'Certificates',
            'Generated',
            'Generated membership certificate ' . $certificateNo . ' for ' . $member->first_name . ' ' . $member->last_name,
            $member->id
        );

        return view('certificates.membership_print', [
            'member' => $member,
            'certificateNo' => $certificateNo,
            'fullName' => $fullName,
            'churchName' => $request->church_name ?? $member->church->church_name ?? '',
            'churchLocation' => $request->church_location ?? 'Buhangin, Davao City',
            'position' => $request->position ?? '',
            'issuedDate' => $request->issued_date ?? now()->toDateString(),
            'secretaryName' => $request->secretary_name ?? '',
            'elderName' => $request->elder_name ?? '',
        ]);
    }
}
