<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BaptismCertificate;
use App\Models\CertificateLog;
use App\Models\Member;
use App\Models\ActivityLog;

class BaptismCertificateController extends Controller
{
    public function print(Request $request)
    {
        $member = Member::with('church')->findOrFail($request->member_id);

        // Auto-generate certificate number (same pattern as Dedication)
        $year = now()->year;
        $count = BaptismCertificate::whereYear('created_at', $year)->count() + 1;
        $certificateNo = 'BAP-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);

        // Save record to baptism_certificates table
        BaptismCertificate::create([
            'certificate_no' => $certificateNo,
            'member_id' => $member->id,

            'full_name' => trim(
                $member->first_name . ' ' .
                ($member->middle_initial ? $member->middle_initial . ' ' : '') .
                $member->last_name . ' ' .
                ($member->suffix ?? '')
            ),

            'gender' => $member->gender,
            'birth_place' => $member->birthplace,
            'birth_date' => $member->birthdate,
            'father_name' => $member->father_name,
            'mother_name' => $member->mother_name,

            'baptism_place' => $request->baptism_place,
            'officiating_minister' => $request->officiating_minister,
            'chairman' => $request->chairman,
            'secretary' => $request->secretary,

            'fellowship_date' => $request->fellowship_date,

            // Combine day/month/year into proper date
            'baptism_date' => ($request->baptism_year && $request->baptism_month && $request->baptism_day)
                ? \Carbon\Carbon::parse($request->baptism_day . ' ' . $request->baptism_month . ' ' . $request->baptism_year)->toDateString()
                : null,

            'church_fellowship' => $request->church_fellowship,

            'doc_no' => $request->doc_no,
            'page_no' => $request->page_no,
            'book_no' => $request->book_no,
            'series_no' => $request->series_no,
        ]);

        // Create CertificateLog entry (same as before, fixed printed_by)
        CertificateLog::create([
            'member_id' => $member->id,
            'certificate_type' => 'Baptism',
            'certificate_number' => $certificateNo,
            'printed_by' => auth()->user()->name ?? 'Admin',
            'printed_at' => now(),
        ]);

        // Activity Log (matches Dedication pattern)
        ActivityLog::log(
            'Certificates',
            'Generated',
            'Generated baptism certificate ' . $certificateNo . ' for ' . $member->first_name . ' ' . $member->last_name,
            $member->id
        );

        return view('certificates.baptism_print', compact('member', 'request', 'certificateNo'));
    }
}
