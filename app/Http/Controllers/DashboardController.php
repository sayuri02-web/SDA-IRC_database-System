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

    /**
     * API endpoint for Vue dashboard
     */
    public function apiData()
    {
        $now = Carbon::now();

        return response()->json([
            'stats' => [
                ['label' => 'Total Members', 'value' => Member::count(), 'icon' => 'mdi-account-group', 'color' => '#667eea'],
                ['label' => 'Churches', 'value' => Church::count(), 'icon' => 'mdi-home-group', 'color' => '#11998e'],
                ['label' => 'Certificates', 'value' => CertificateLog::count(), 'icon' => 'mdi-certificate', 'color' => '#f093fb'],
                ['label' => 'Templates', 'value' => Certificate::count(), 'icon' => 'mdi-file-document-multiple', 'color' => '#7f53ac'],
                ['label' => 'New This Month', 'value' => Member::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count(), 'icon' => 'mdi-account-plus', 'color' => '#4facfe'],
                ['label' => 'Activities Today', 'value' => Task::whereDate('due_date', $now->toDateString())->count(), 'icon' => 'mdi-lightning-bolt', 'color' => '#fa709a'],
            ],
            'memberGrowth' => $this->getMemberGrowth($now),
            'membershipStatus' => [
                'baptized' => Member::where('membership_status', 'baptized')->count(),
                'dedicated' => Member::where('membership_status', 'dedicated')->count(),
                'na' => Member::where('membership_status', 'na')->count(),
            ],
            'certStats' => [
                'today' => CertificateLog::whereDate('created_at', $now->toDateString())->count(),
                'week' => CertificateLog::where('created_at', '>=', $now->startOfWeek())->count(),
                'month' => CertificateLog::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count(),
            ],
            'certTrend' => $this->getCertTrend($now),
            'recentActivities' => ActivityLog::where(
                'created_at',
                '>=',
                now()->subHours(24)
            )
            ->latest()
            ->take(20)
            ->get()
            ->map(fn($a) => [
                'user' => $a->user_name,
                'description' => $a->description,
                'module' => $a->module,
                'time' => $a->created_at->diffForHumans(),
                'created_at' => $a->created_at->toDateTimeString(),
            ]),

            'tasks' => Task::latest()->get()->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'status' => $t->status,
                'completed' => $t->completed,
                'due_date' => $t->due_date?->format('M d, Y'),
            ]),
        ]);
    }

    private function getMemberGrowth($now)
    {
        $growth = Member::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $now->copy()->subMonths(11)->startOfMonth())
            ->groupBy('month')->orderBy('month')->pluck('count', 'month')->toArray();

        $labels = []; $data = [];
        for ($i = 11; $i >= 0; $i--) {
            $key = $now->copy()->subMonths($i)->format('Y-m');
            $labels[] = $now->copy()->subMonths($i)->format('M Y');
            $data[] = $growth[$key] ?? 0;
        }
        return ['labels' => $labels, 'data' => $data];
    }

    private function getCertTrend($now)
    {
        $trend = CertificateLog::select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', $now->copy()->subMonths(5)->startOfMonth())
            ->groupBy('month')->orderBy('month')->pluck('count', 'month')->toArray();

        $labels = []; $data = [];
        for ($i = 5; $i >= 0; $i--) {
            $key = $now->copy()->subMonths($i)->format('Y-m');
            $labels[] = $now->copy()->subMonths($i)->format('M');
            $data[] = $trend[$key] ?? 0;
        }
        return ['labels' => $labels, 'data' => $data];
    }
}

// Note: apiData method is appended below the class closing brace.
// It needs to be INSIDE the class. Let me fix this via a proper edit.
