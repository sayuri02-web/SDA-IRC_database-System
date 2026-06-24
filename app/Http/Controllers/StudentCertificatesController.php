<?php

namespace App\Http\Controllers;

use App\Models\StudentCertificates;
use App\Models\Member;
use App\Models\CertificateLog;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class StudentCertificatesController extends Controller
{
    /**
     * Search members for student certificate
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
     * Show student certificate form for a member
     */
    public function form($id)
    {
        $member = Member::with('church')->findOrFail($id);

        return view('certificates.studentcert', compact('member'));
    }

    /**
     * Generate and print student certificate
     */
    public function print(Request $request)
    {
        $member = Member::with('church')->findOrFail($request->member_id);

        // Auto-generate certificate number
        $year = now()->year;
        $count = StudentCertificates::whereYear('created_at', $year)->count() + 1;
        $certificateNo = 'STU-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Build full name with prefix
        $prefix = $member->gender === 'Female' ? 'Sis.' : 'Bro.';
        $fullName = trim(
            $prefix . ' ' .
            $member->first_name . ' ' .
            ($member->middle_initial ? $member->middle_initial . '. ' : '') .
            $member->last_name .
            ($member->suffix ? ' ' . $member->suffix : '')
        );

        // Save record
        StudentCertificates::create([
            'certificate_no' => $certificateNo,
            'member_id' => $member->id,
            'full_name' => $fullName,
            'church_name' => $request->church_name ?? $member->church->church_name ?? '',
            'church_location' => $request->church_location ?? '',
            'issued_date' => $request->issued_date ?? now()->toDateString(),
            'elder_1' => $request->elder_1 ?? '',
            'elder_2' => $request->elder_2 ?? '',
            'elder_3' => $request->elder_3 ?? '',
            'elder_4' => $request->elder_4 ?? '',
            'issued_by' => auth()->user()->name ?? 'Admin',
        ]);

        // Create CertificateLog entry
        CertificateLog::create([
            'member_id' => $member->id,
            'certificate_type' => 'Student',
            'certificate_number' => $certificateNo,
            'printed_by' => auth()->user()->name ?? 'Admin',
            'printed_at' => now(),
        ]);

        // Activity Log
        ActivityLog::log(
            'Certificates',
            'Generated',
            'Generated student certificate ' . $certificateNo . ' for ' . $member->first_name . ' ' . $member->last_name,
            $member->id
        );

        // Determine pronoun
        $pronoun = $member->gender === 'Female' ? 'she' : 'he';
        $possessive = $member->gender === 'Female' ? 'her' : 'his';
        $honorific = $member->gender === 'Female' ? 'Ms.' : 'Mr.';

        $displayName = trim(
            $honorific . ' ' .
            $member->first_name . ' ' .
            ($member->middle_initial ? $member->middle_initial . '. ' : '') .
            $member->last_name .
            ($member->suffix ? ' ' . $member->suffix : '')
        );

        return view('certificates.studentcert_print', [
            'member' => $member,
            'certificateNo' => $certificateNo,
            'fullName' => $fullName,
            'displayName' => $displayName,
            'churchLocation' => $request->church_location ?? 'Buhangin, Davao City',
            'issuedDate' => $request->issued_date ?? now()->toDateString(),
            'pronoun' => $pronoun,
            'possessive' => $possessive,
            'elder1' => $request->elder_1 ?? '',
            'elder2' => $request->elder_2 ?? '',
            'elder3' => $request->elder_3 ?? '',
            'elder4' => $request->elder_4 ?? '',
        ]);
    }
}
