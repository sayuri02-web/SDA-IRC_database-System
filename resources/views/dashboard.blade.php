@extends('layout')

@section('content')

<div class="dashboard-wrapper">

    {{-- SUMMARY CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="dash-card" data-aos="fade-up" data-aos-delay="0">
                <div class="dash-card-icon" style="background:#eef4ff; color:#2449d8;">
                    <i class="mdi mdi-account-group"></i>
                </div>
                <div class="dash-card-num">{{ $totalMembers }}</div>
                <div class="dash-card-label">Total Members</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="dash-card" data-aos="fade-up" data-aos-delay="80">
                <div class="dash-card-icon" style="background:#e9fff3; color:#28a745;">
                    <i class="mdi mdi-church"></i>
                </div>
                <div class="dash-card-num">{{ $totalChurches }}</div>
                <div class="dash-card-label">Churches</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="dash-card" data-aos="fade-up" data-aos-delay="160">
                <div class="dash-card-icon" style="background:#fff4e8; color:#ff8a00;">
                    <i class="mdi mdi-certificate"></i>
                </div>
                <div class="dash-card-num">{{ $totalCertificates }}</div>
                <div class="dash-card-label">Certificates</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="dash-card" data-aos="fade-up" data-aos-delay="240">
                <div class="dash-card-icon" style="background:#f7ecff; color:#8e3dff;">
                    <i class="mdi mdi-file-document-multiple"></i>
                </div>
                <div class="dash-card-num">{{ $totalTemplates }}</div>
                <div class="dash-card-label">Templates</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="dash-card" data-aos="fade-up" data-aos-delay="320">
                <div class="dash-card-icon" style="background:#e0f7fa; color:#0097a7;">
                    <i class="mdi mdi-account-plus"></i>
                </div>
                <div class="dash-card-num">{{ $newMembersThisMonth }}</div>
                <div class="dash-card-label">New This Month</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="dash-card" data-aos="fade-up" data-aos-delay="400">
                <div class="dash-card-icon" style="background:#fff0f0; color:#e53935;">
                    <i class="mdi mdi-lightning-bolt"></i>
                </div>
                <div class="dash-card-num">{{ $activitiesToday }}</div>
                <div class="dash-card-label">Activities Today</div>
            </div>
        </div>
    </div>

    {{-- CHARTS ROW --}}
    <div class="row g-3 mb-4">
        {{-- MEMBER GROWTH CHART --}}
        <div class="col-lg-8">
            <div class="dash-panel" data-aos="fade-up" data-aos-delay="100">
                <div class="dash-panel-header">
                    <h5 class="dash-panel-title"><i class="mdi mdi-chart-line me-2"></i>Member Growth</h5>
                    <span class="dash-panel-badge">Last 12 Months</span>
                </div>
                <canvas id="memberGrowthChart" height="100"></canvas>
            </div>
        </div>

        {{-- MEMBERSHIP BREAKDOWN --}}
        <div class="col-lg-4">
            <div class="dash-panel" data-aos="fade-up" data-aos-delay="200">
                <div class="dash-panel-header">
                    <h5 class="dash-panel-title"><i class="mdi mdi-chart-donut me-2"></i>Membership Status</h5>
                </div>
                <canvas id="membershipChart" height="170"></canvas>
                <div class="dash-legend mt-3">
                    <span class="dash-legend-item"><span class="dash-dot" style="background:#28a745;"></span>Baptized ({{ $baptizedCount }})</span>
                    <span class="dash-legend-item"><span class="dash-dot" style="background:#2449d8;"></span>Dedicated ({{ $dedicatedCount }})</span>
                    <span class="dash-legend-item"><span class="dash-dot" style="background:#8898aa;"></span>N/A ({{ $naCount }})</span>
                </div>
            </div>
        </div>
    </div>

    {{-- CERTIFICATE STATS + QUICK TASKS --}}
    <div class="row g-3 mb-4">
        {{-- CERTIFICATE OVERVIEW --}}
        <div class="col-lg-4">
            <div class="dash-panel" data-aos="fade-up" data-aos-delay="100">
                <div class="dash-panel-header">
                    <h5 class="dash-panel-title"><i class="mdi mdi-certificate me-2"></i>Certificates</h5>
                </div>
                <div class="dash-cert-stats">
                    <div class="dash-cert-stat">
                        <span class="dash-cert-num">{{ $certsToday }}</span>
                        <span class="dash-cert-label">Today</span>
                    </div>
                    <div class="dash-cert-stat">
                        <span class="dash-cert-num">{{ $certsWeek }}</span>
                        <span class="dash-cert-label">This Week</span>
                    </div>
                    <div class="dash-cert-stat">
                        <span class="dash-cert-num">{{ $certsMonth }}</span>
                        <span class="dash-cert-label">This Month</span>
                    </div>
                </div>
                <canvas id="certTrendChart" height="120" class="mt-3"></canvas>
            </div>
        </div>

        {{-- QUICK TASKS WIDGET --}}
        <div class="col-lg-8">
            <div class="dash-tasks-widget">
                <div class="dash-panel-header">
                    <h5 class="dash-panel-title"><i class="mdi mdi-checkbox-marked-outline me-2"></i>Quick Tasks</h5>
                </div>

                {{-- INPUT --}}
                <div class="dash-tasks-input">
                    <input type="text" class="dash-tasks-field" placeholder="What do you need to do today?" disabled>
                    <button class="dash-tasks-add-btn" disabled>Add</button>
                </div>

                {{-- TASK LIST --}}
                <div class="dash-tasks-list">
                    <div class="dash-task-item">
                        <i class="mdi mdi-checkbox-blank-outline dash-task-check"></i>
                        <span class="dash-task-text">Pick up kids from school</span>
                        <i class="mdi mdi-close-circle-outline dash-task-delete"></i>
                    </div>
                    <div class="dash-task-item dash-task-done">
                        <i class="mdi mdi-checkbox-marked dash-task-check"></i>
                        <span class="dash-task-text">Prepare for presentation</span>
                        <i class="mdi mdi-close-circle-outline dash-task-delete"></i>
                    </div>
                    <div class="dash-task-item">
                        <i class="mdi mdi-checkbox-blank-outline dash-task-check"></i>
                        <span class="dash-task-text">Print Statements</span>
                        <i class="mdi mdi-close-circle-outline dash-task-delete"></i>
                    </div>
                    <div class="dash-task-item">
                        <i class="mdi mdi-checkbox-blank-outline dash-task-check"></i>
                        <span class="dash-task-text">Create Invoice</span>
                        <i class="mdi mdi-close-circle-outline dash-task-delete"></i>
                    </div>
                    <div class="dash-task-item dash-task-done">
                        <i class="mdi mdi-checkbox-marked dash-task-check"></i>
                        <span class="dash-task-text">Call John</span>
                        <i class="mdi mdi-close-circle-outline dash-task-delete"></i>
                    </div>
                    <div class="dash-task-item">
                        <i class="mdi mdi-checkbox-blank-outline dash-task-check"></i>
                        <span class="dash-task-text">Meeting with Alisa</span>
                        <i class="mdi mdi-close-circle-outline dash-task-delete"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RECENT ACTIVITIES --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-12">
            <div class="dash-panel" data-aos="fade-up" data-aos-delay="200">
                <div class="dash-panel-header">
                    <h5 class="dash-panel-title"><i class="mdi mdi-history me-2"></i>Recent Activities</h5>
                    <span class="dash-panel-badge">Latest 20</span>
                </div>
                <div class="dash-activity-list">
                    @forelse($recentActivities as $activity)
                    <div class="dash-activity-item">
                        <div class="dash-activity-avatar">
                            {{ strtoupper(substr($activity->user_name, 0, 1)) }}
                        </div>
                        <div class="dash-activity-info">
                            <div class="dash-activity-desc">
                                <strong>{{ $activity->user_name }}</strong> {{ $activity->description }}
                            </div>
                            <div class="dash-activity-meta">
                                <span class="dash-activity-module">{{ $activity->module }}</span>
                                <span class="dash-activity-time">{{ $activity->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="dash-activity-empty">
                        <i class="mdi mdi-history" style="font-size:36px; color:#c4c9d4;"></i>
                        <p class="text-muted mt-2 mb-0">No activities recorded yet.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>{{-- end dashboard-wrapper --}}

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Member Growth Chart
    new Chart(document.getElementById('memberGrowthChart'), {
        type: 'line',
        data: {
            labels: @json($monthLabels),
            datasets: [{
                label: 'New Members',
                data: @json($monthData),
                borderColor: '#28a745',
                backgroundColor: 'rgba(40, 167, 69, 0.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#28a745',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });

    // Membership Status Chart
    new Chart(document.getElementById('membershipChart'), {
        type: 'doughnut',
        data: {
            labels: ['Baptized', 'Dedicated', 'N/A'],
            datasets: [{
                data: [{{ $baptizedCount }}, {{ $dedicatedCount }}, {{ $naCount }}],
                backgroundColor: ['#28a745', '#2449d8', '#8898aa'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });

    // Certificate Trend Chart
    new Chart(document.getElementById('certTrendChart'), {
        type: 'bar',
        data: {
            labels: @json($certLabels),
            datasets: [{
                label: 'Certificates',
                data: @json($certData),
                backgroundColor: 'rgba(40, 167, 69, 0.7)',
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { stepSize: 1 } },
                x: { grid: { display: false } }
            }
        }
    });
});
</script>
@endpush

@endsection
