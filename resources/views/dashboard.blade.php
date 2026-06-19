@extends('layout')

@section('content')

<div class="dashboard-wrapper">

    {{-- SUMMARY CARDS (gradient style) --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="dash-card dash-card-gradient-blue" data-aos="fade-up" data-aos-delay="0">
                <div class="dash-card-icon">
                    <i class="mdi mdi-account-group"></i>
                </div>
                <div class="dash-card-num">{{ $totalMembers }}</div>
                <div class="dash-card-label">Total Members</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="dash-card dash-card-gradient-green" data-aos="fade-up" data-aos-delay="80">
                <div class="dash-card-icon">
                    <i class="mdi mdi-home"></i>
                </div>
                <div class="dash-card-num">{{ $totalChurches }}</div>
                <div class="dash-card-label">Churches</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="dash-card dash-card-gradient-orange" data-aos="fade-up" data-aos-delay="160">
                <div class="dash-card-icon">
                    <i class="mdi mdi-certificate"></i>
                </div>
                <div class="dash-card-num">{{ $totalCertificates }}</div>
                <div class="dash-card-label">Certificates</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="dash-card dash-card-gradient-purple" data-aos="fade-up" data-aos-delay="240">
                <div class="dash-card-icon">
                    <i class="mdi mdi-file-document-multiple"></i>
                </div>
                <div class="dash-card-num">{{ $totalTemplates }}</div>
                <div class="dash-card-label">Templates</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="dash-card dash-card-gradient-teal" data-aos="fade-up" data-aos-delay="320">
                <div class="dash-card-icon">
                    <i class="mdi mdi-account-plus"></i>
                </div>
                <div class="dash-card-num">{{ $newMembersThisMonth }}</div>
                <div class="dash-card-label">New This Month</div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="dash-card dash-card-gradient-red" data-aos="fade-up" data-aos-delay="400">
                <div class="dash-card-icon">
                    <i class="mdi mdi-lightning-bolt"></i>
                </div>
                <div class="dash-card-num">{{ $activitiesToday }}</div>
                <div class="dash-card-label">Activities Today</div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
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
        
        <div class="col-lg-8">
            <div class="dash-tasks-widget" data-aos="fade-up" data-aos-delay="100">
                <div class="dash-panel-header">
                    <h5 class="dash-panel-title"><i class="mdi mdi-checkbox-marked-outline me-2"></i>Quick Tasks</h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#addTaskModal">
                            <i class="mdi mdi-plus me-1"></i>Add Task
                        </button>
                        <button class="btn btn-outline-primary btn-sm" id="btnUpdateStatus" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                            <i class="mdi mdi-pencil-outline me-1"></i>Update Status
                        </button>
                    </div>
                </div>
                <hr class="mb-4">

                {{-- TASK LIST --}}
                <div class="dash-tasks-list" id="taskList">
                    @forelse($tasks as $task)
                    <div class="dash-task-item {{ $task->completed ? 'dash-task-done' : '' }}" data-id="{{ $task->id }}">
                        <input type="checkbox" class="task-cb" value="{{ $task->id }}" {{ $task->completed ? 'checked' : '' }}>
                        <span class="dash-task-text">{{ $task->name }}</span>
                        <span class="dash-task-due">{{ $task->due_date ? $task->due_date->format('M d, Y') : '—' }}</span>
                        <span class="dash-task-status dash-task-status-{{ $task->status }}">{{ ucfirst($task->status) }}</span>
                        <i class="mdi mdi-close-circle-outline dash-task-delete" data-id="{{ $task->id }}"></i>
                    </div>
                    @empty
                    <div class="dash-task-empty" id="taskEmpty">
                        <i class="mdi mdi-checkbox-marked-outline" style="font-size:32px; color:#d0d0d0;"></i>
                        <p class="text-muted mt-2 mb-0" style="font-size:13px;">No tasks yet. Add one above.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ADD TASK MODAL --}}
    <div class="modal fade" id="addTaskModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content" style="border-radius:16px; border:none;">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold">Add Task</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="text" id="taskNameInput" class="form-control mb-3" placeholder="Task name..." style="border-radius:10px; height:42px;">
                    <input type="date" id="taskDueDateInput" class="form-control mb-3" style="border-radius:10px; height:42px;" value="{{ date('Y-m-d') }}">
                    <button class="btn btn-outline-success w-100" id="btnSaveTask">Save Task</button>
                </div>
            </div>
        </div>
    </div>

    {{-- UPDATE STATUS MODAL --}}
    <div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content" style="border-radius:16px; border:none;">
                <div class="modal-header border-0 pb-0">
                    <h6 class="modal-title fw-bold">Update Task Status</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    {{-- DATE --}}
                    <label class="form-label fw-semibold" style="font-size:13px;">Select Date</label>
                    <input type="date" id="statusDateSelect" class="form-control mb-3" style="border-radius:10px; height:42px;" value="{{ date('Y-m-d') }}">

                    {{-- TASK --}}
                    <label class="form-label fw-semibold" style="font-size:13px;">Select Task</label>
                    <select id="statusTaskSelect" class="form-select mb-3" style="border-radius:10px; height:42px;" disabled>
                        <option value="">— Choose task —</option>
                    </select>

                    {{-- STATUS --}}
                    <label class="form-label fw-semibold" style="font-size:13px;">New Status</label>
                    <select id="statusSelect" class="form-select mb-3" style="border-radius:10px; height:42px;">
                        <option value="pending">Pending</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                    </select>

                    <button class="btn btn-outline-primary w-100" id="btnConfirmStatus">Save Status</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ROW 3: CERTIFICATE STATS + RECENT ACTIVITIES (moved here) --}}
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

        {{-- RECENT ACTIVITIES (moved from bottom to Quick Tasks old position) --}}
        <div class="col-lg-8">
            <div class="dash-panel dash-panel-activities" data-aos="fade-up" data-aos-delay="200">
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
                                <span class="dash-activity-time">
                                    <i class="mdi mdi-clock-outline me-1"></i>{{ $activity->created_at->diffForHumans() }}
                                </span>
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // ========== CHARTS ==========
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
        options: { responsive: true, cutout: '70%', plugins: { legend: { display: false } } }
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
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } }, x: { grid: { display: false } } } }
    });

    // ========== QUICK TASKS ==========
    const taskList = document.getElementById('taskList');

    // Checkbox change — toggle task completed/pending immediately
    taskList.addEventListener('change', function(e) {
        if (!e.target.classList.contains('task-cb')) return;
        const id = e.target.value;

        fetch('/task/toggle/' + id, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const row = e.target.closest('.dash-task-item');
                const statusBadge = row.querySelector('.dash-task-status');
                if (e.target.checked) {
                    row.classList.add('dash-task-done');
                    statusBadge.className = 'dash-task-status dash-task-status-completed';
                    statusBadge.textContent = 'Completed';
                } else {
                    row.classList.remove('dash-task-done');
                    statusBadge.className = 'dash-task-status dash-task-status-pending';
                    statusBadge.textContent = 'Pending';
                }
            }
        });
    });

    // Add Task
    document.getElementById('btnSaveTask').addEventListener('click', function() {
        const name = document.getElementById('taskNameInput').value.trim();
        const dueDate = document.getElementById('taskDueDateInput').value;
        if (!name) return;

        fetch('/task/store', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ name: name, due_date: dueDate })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) location.reload();
        });
    });

    // Delete task (uses global delete modal)
    let taskDeleteId = null;
    taskList.addEventListener('click', function(e) {
        const del = e.target.closest('.dash-task-delete');
        if (!del) return;
        taskDeleteId = del.dataset.id;
        document.getElementById('globalDeleteTitle').textContent = 'Delete Task';
        document.getElementById('globalDeleteMsg').textContent = 'Are you sure you want to delete this task? This action cannot be undone.';
        window._dashTaskDeleteFn = function() {
            fetch('/task/delete/' + taskDeleteId, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken }
            }).then(r => r.json()).then(d => { if(d.success) location.reload(); });
        };
        // Override global confirm to use callback
        const confirmBtn = document.getElementById('globalDeleteConfirmBtn');
        const newBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newBtn, confirmBtn);
        newBtn.addEventListener('click', function() {
            window._dashTaskDeleteFn();
            bootstrap.Modal.getInstance(document.getElementById('globalDeleteModal')).hide();
        });
        new bootstrap.Modal(document.getElementById('globalDeleteModal')).show();
    });

    // ========== UPDATE STATUS MODAL ==========
    const dateSelect = document.getElementById('statusDateSelect');
    const taskSelect = document.getElementById('statusTaskSelect');

    // Load tasks when date is changed
    dateSelect.addEventListener('change', function() {
        const date = this.value;
        if (!date) { taskSelect.innerHTML = '<option value="">— Choose task —</option>'; taskSelect.disabled = true; return; }

        fetch('/task/by-date?date=' + date)
        .then(r => r.json())
        .then(tasks => {
            taskSelect.innerHTML = '<option value="">— Choose task —</option>';
            tasks.forEach(t => {
                taskSelect.innerHTML += '<option value="' + t.id + '">' + t.name + ' (' + t.status + ')</option>';
            });
            taskSelect.disabled = tasks.length === 0;
        });
    });

    // Auto-load tasks for today when modal opens
    document.getElementById('updateStatusModal').addEventListener('show.bs.modal', function() {
        dateSelect.dispatchEvent(new Event('change'));
    });

    // Save status
    document.getElementById('btnConfirmStatus').addEventListener('click', function() {
        const taskId = taskSelect.value;
        const status = document.getElementById('statusSelect').value;
        if (!taskId) { alert('Please select a task.'); return; }

        fetch('/task/update-status', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ task_id: taskId, status: status })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) location.reload();
        });
    });
});
</script>
@endpush

@endsection
