<?php

namespace App\Http\Controllers;

use App\Models\CounselingCertificate;
use App\Models\Member;
use App\Models\CertificateLog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CounselingCertificateController extends Controller
{
    /**
     * Search members for counseling certificate
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
     * Show counseling certificate form for a member
     */
    public function form($id)
    {
        $member = Member::with('church')->findOrFail($id);

        return view('certificates.counseling', compact('member'));
    }

    /**
     * Generate and print counseling certificate
     */
    public function print(Request $request)
    {
        $member = Member::with('church')->findOrFail($request->member_id);

        // Auto-generate certificate number
        $year = now()->year;
        $count = CounselingCertificate::whereYear('created_at', $year)->count() + 1;
        $certificateNo = 'COU-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        $fullName = trim(
            $member->first_name . ' ' .
            ($member->middle_initial ? $member->middle_initial . ' ' : '') .
            $member->last_name .
            ($member->suffix ? ' ' . $member->suffix : '')
        );

        // Save record
        CounselingCertificate::create([
            'certificate_no' => $certificateNo,
            'member_id' => $member->id,
            'full_name' => $fullName,
            'partner_name' => $request->partner_name ?? '',
            'church_name' => $request->church_name ?? $member->church->church_name ?? '',
            'church_location' => $request->church_location ?? '',
            'purpose' => $request->purpose ?? '',
            'issued_date' => $request->issued_date ?? now()->toDateString(),
            'chairman_name' => $request->chairman_name ?? '',
            'issued_by' => auth()->user()->name ?? 'Admin',
        ]);

        // Create CertificateLog entry
        CertificateLog::create([
            'member_id' => $member->id,
            'certificate_type' => 'Counseling',
            'certificate_number' => $certificateNo,
            'printed_by' => auth()->user()->name ?? 'Admin',
            'printed_at' => now(),
        ]);

        // Activity Log
        ActivityLog::log(
            'Certificates',
            'Generated',
            'Generated counseling certificate ' . $certificateNo . ' for ' . $fullName,
            $member->id
        );

        return view('certificates.counseling_print', [
            'member' => $member,
            'certificateNo' => $certificateNo,
            'fullName' => $fullName,
            'partnerName' => $request->partner_name ?? '',
            'churchName' => $request->church_name ?? $member->church->church_name ?? '',
            'churchLocation' => $request->church_location ?? 'Buhangin, Davao City',
            'purpose' => $request->purpose ?? 'to maintain their behavior to the church and to the community to insert knowledge of population and control.',
            'issuedDate' => $request->issued_date ?? now()->toDateString(),
            'chairmanName' => $request->chairman_name ?? '',
        ]);
    }
}
