<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Church;
use App\Models\Certificate;
use App\Models\CertificateLog;
use App\Models\Organization;
use App\Models\Event;
use App\Models\Announcement;
use App\Models\Ministry;
use App\Models\GalleryAlbum;
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
                ['label' => 'Total Members', 'value' => Member::count(), 'icon' => 'mdi-account-group', 'color' => '#667eea', 'link' => '/members'],
                ['label' => 'Churches', 'value' => Church::count(), 'icon' => 'mdi-home-group', 'color' => '#11998e', 'link' => '/church'],
                ['label' => 'Certificates', 'value' => CertificateLog::count(), 'icon' => 'mdi-certificate', 'color' => '#f093fb', 'link' => '/certificates'],
                ['label' => 'Organizations', 'value' => Organization::count(), 'icon' => 'mdi-account-multiple-outline', 'color' => '#7f53ac', 'link' => '/leaders-directory'],
                [
                    'label' => 'Published / Draft',
                    'icon' => 'mdi-web',
                    'color' => '#4facfe',
                    'link' => '/website-management',
                    'dual' => true,
                    'published' => Event::where('is_published', true)->count() + Announcement::where('is_published', true)->count() + Ministry::where('is_published', true)->count() + GalleryAlbum::where('is_published', true)->count(),
                    'draft' => Event::where('is_published', false)->count() + Announcement::where('is_published', false)->count() + Ministry::where('is_published', false)->count() + GalleryAlbum::where('is_published', false)->count(),
                ],
            ],
            'activitiesToday' => Task::where('completed', false)
                ->where(function ($q) use ($now) {
                    $q->whereDate('start_date', '<=', $now->toDateString())
                      ->whereDate('end_date', '>=', $now->toDateString());
                })->count(),
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

            'tasks' => Task::latest()->get()->each(function ($t) {
                if (!$t->completed && $t->end_date && $t->end_date->lt(now()->startOfDay())) {
                    if ($t->status !== 'overdue') {
                        $t->status = 'overdue';
                        $t->save();
                    }
                }
            })->map(fn($t) => [
                'id'         => $t->id,
                'name'       => $t->name,
                'status'     => $t->status,
                'completed'  => $t->completed,
                'start_date' => $t->start_date?->format('Y-m-d'),
                'end_date'   => $t->end_date?->format('Y-m-d'),
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
