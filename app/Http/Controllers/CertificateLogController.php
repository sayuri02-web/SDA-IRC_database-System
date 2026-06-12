<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CertificateLog;
use App\Models\Member;
use App\Models\ActivityLog;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateLogController extends Controller
{
    // 1. SHOW LOGS
    public function index()
    {
        $logs = CertificateLog::with('member')
            ->latest()
            ->get();

        return view('certificate_logs.index', compact('logs'));
    }

    // 2. GENERATE + SAVE LOG (IMPORTANT PART)
    public function store(Request $request)
    {
        $member = Member::findOrFail($request->member_id);

        $year = now()->year;

        $count = CertificateLog::whereYear('created_at', $year)
        ->lockForUpdate()
        ->count() + 1;

        $certificateNumber =
            'BAP-' .
            $year .
            '-' .
            str_pad($count, 4, '0', STR_PAD_LEFT);

        CertificateLog::create([
            'member_id' => $member->id,
            'certificate_type' => 'Baptism',
            'certificate_number' => $certificateNumber,
            'printed_by' => auth()->user()->name ?? 'Admin',
            'printed_at' => now(),
        ]);

        ActivityLog::log('Certificates', 'Generated', 'Generated certificate ' . $certificateNumber . ' for ' . $member->first_name . ' ' . $member->last_name, $member->id);

        return back()->with('success', 'Certificate printed & logged!');
    }

    // 3. DOWNLOAD PDF
    public function download($id)
    {
        $log = CertificateLog::with('member')->findOrFail($id);

        $pdf = Pdf::loadView('certificates.baptism_print', [
            'member' => $log->member,
            'certificateNumber' => $log->certificate_number
        ]);

        return $pdf->download($log->certificate_number . '.pdf');
    }
}