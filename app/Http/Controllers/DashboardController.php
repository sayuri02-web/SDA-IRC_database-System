<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Church;
use App\Models\Certificate;
use App\Models\CertificateLog;
use App\Models\ActivityLog;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $now = Carbon::now();

        // ========== SUMMARY CARDS ==========
        $totalMembers       = Member::count();
        $totalChurches      = Church::count();
        $totalCertificates  = CertificateLog::count();
        $totalTemplates     = Certificate::count();
        $newMembersThisMonth = Member::whereMonth('created_at', $now->month)
                                     ->whereYear('created_at', $now->year)
                                     ->count();
        $activitiesToday    = Task::whereDate('due_date', $now->toDateString())->count();

        // ========== QUICK TASKS ==========
        $tasks = Task::latest()->get();

        // ========== MEMBER GROWTH (last 12 months) ==========
        $memberGrowth = Member::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $monthLabels = [];
        $monthData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $key = $now->copy()->subMonths($i)->format('Y-m');
            $label = $now->copy()->subMonths($i)->format('M Y');
            $monthLabels[] = $label;
            $monthData[]   = $memberGrowth[$key] ?? 0;
        }

        // ========== MEMBERSHIP STATUS BREAKDOWN ==========
        $baptizedCount   = Member::where('membership_status', 'baptized')->count();
        $dedicatedCount  = Member::where('membership_status', 'dedicated')->count();
        $naCount         = Member::where('membership_status', 'na')->count();

        // ========== CERTIFICATE STATS ==========
        $certsToday  = CertificateLog::whereDate('created_at', $now->toDateString())->count();
        $certsWeek   = CertificateLog::where('created_at', '>=', $now->startOfWeek())->count();
        $certsMonth  = CertificateLog::whereMonth('created_at', $now->month)
                                     ->whereYear('created_at', $now->year)
                                     ->count();

        // Certificate monthly trend (last 6 months)
        $certTrend = CertificateLog::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', $now->copy()->subMonths(5)->startOfMonth())
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $certLabels = [];
        $certData   = [];
        for ($i = 5; $i >= 0; $i--) {
            $key = $now->copy()->subMonths($i)->format('Y-m');
            $label = $now->copy()->subMonths($i)->format('M');
            $certLabels[] = $label;
            $certData[]   = $certTrend[$key] ?? 0;
        }

        // ========== RECENT ACTIVITIES ==========
        $recentActivities = ActivityLog::latest()->take(20)->get();

        // ========== TODAY'S ACTIVITY SUMMARY ==========
        $membersAddedToday   = ActivityLog::whereDate('created_at', $now->toDateString())
                                          ->where('module', 'Members')
                                          ->where('action', 'Created')
                                          ->count();
        $membersUpdatedToday = ActivityLog::whereDate('created_at', $now->toDateString())
                                          ->where('module', 'Members')
                                          ->where('action', 'Updated')
                                          ->count();
        $certsGeneratedToday = ActivityLog::whereDate('created_at', $now->toDateString())
                                          ->where('module', 'Certificates')
                                          ->count();

        return view('dashboard', compact(
            'totalMembers',
            'totalChurches',
            'totalCertificates',
            'totalTemplates',
            'newMembersThisMonth',
            'activitiesToday',
            'tasks',
            'monthLabels',
            'monthData',
            'baptizedCount',
            'dedicatedCount',
            'naCount',
            'certsToday',
            'certsWeek',
            'certsMonth',
            'certLabels',
            'certData',
            'recentActivities',
            'membersAddedToday',
            'membersUpdatedToday',
            'certsGeneratedToday'
        ));
    }
}
