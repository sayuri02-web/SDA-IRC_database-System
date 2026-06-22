<template>
    <div class="dashboard-wrapper">

        <!-- LOADING STATE -->
        <div v-if="loading" class="dash-loading">
            <div class="row g-3 mb-4">
                <div v-for="n in 6" :key="n" class="col-lg-2 col-md-4 col-sm-6">
                    <div class="dash-skeleton dash-skeleton-card"></div>
                </div>
            </div>
            <div class="row g-3">
                <div class="col-lg-8"><div class="dash-skeleton dash-skeleton-chart"></div></div>
                <div class="col-lg-4"><div class="dash-skeleton dash-skeleton-chart"></div></div>
            </div>
        </div>

        <!-- LOADED STATE -->
        <template v-else>

            <!-- STAT CARDS -->
            <div class="row g-3 mb-4">
                <div v-for="(stat, i) in stats" :key="i" class="col-lg-2 col-md-4 col-sm-6">
                    <div class="dash-card" :style="{ background: stat.gradient }">
                        <div class="dash-card-icon"><i :class="'mdi ' + stat.icon"></i></div>
                        <div class="dash-card-num">{{ animatedValues[i] }}</div>
                        <div class="dash-card-label">{{ stat.label }}</div>
                    </div>
                </div>
            </div>

            <!-- QUICK TASKS + MEMBERSHIP STATUS -->
            <div class="row g-3 mb-4">
                <!-- QUICK TASKS (replaces Member Growth) -->
                <div class="col-lg-8">
                    <div class="dash-tasks-widget">
                        <div class="dash-panel-header">
                            <h5 class="dash-panel-title"><i class="mdi mdi-checkbox-marked-outline me-2"></i>Quick Tasks</h5>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-success btn-sm" @click="showAddTask = true"><i class="mdi mdi-plus me-1"></i>Add Task</button>
                                <button class="btn btn-outline-primary btn-sm" @click="showUpdateStatus = true"><i class="mdi mdi-pencil-outline me-1"></i>Update Status</button>
                            </div>
                        </div>
                        <hr class="mb-3">
                        <div class="dash-tasks-list">
                            <div v-if="tasks.length === 0" class="dash-task-empty">
                                <i class="mdi mdi-checkbox-marked-outline" style="font-size:32px; color:#d0d0d0;"></i>
                                <p class="text-muted mt-2 mb-0" style="font-size:13px;">No tasks yet. Add one above.</p>
                            </div>
                            <div v-for="task in tasks" :key="task.id" class="dash-task-item" :class="{ 'dash-task-done': task.completed }">
                                <input type="checkbox" class="task-cb" :checked="task.completed" @change="toggleTask(task)">
                                <span class="dash-task-text">{{ task.name }}</span>
                                <span class="dash-task-due">{{ task.due_date || '—' }}</span>
                                <span class="dash-task-status" :class="'dash-task-status-' + task.status">{{ capitalize(task.status) }}</span>
                                <i class="mdi mdi-close-circle-outline dash-task-delete" @click="deleteTask(task)"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MEMBERSHIP STATUS -->
                <div class="col-lg-4">
                    <div class="dash-panel">
                        <div class="dash-panel-header">
                            <h5 class="dash-panel-title"><i class="mdi mdi-chart-donut me-2"></i>Membership Status</h5>
                        </div>
                        <canvas ref="statusChart" height="170"></canvas>
                        <div class="dash-legend mt-3">
                            <span class="dash-legend-item"><span class="dash-dot" style="background:#28a745;"></span>Baptized ({{ data.membershipStatus?.baptized || 0 }})</span>
                            <span class="dash-legend-item"><span class="dash-dot" style="background:#2449d8;"></span>Dedicated ({{ data.membershipStatus?.dedicated || 0 }})</span>
                            <span class="dash-legend-item"><span class="dash-dot" style="background:#8898aa;"></span>N/A ({{ data.membershipStatus?.na || 0 }})</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CERT STATS + ACTIVITIES -->
            <div class="row g-3 mb-4">
                <div class="col-lg-4">
                    <div class="dash-panel">
                        <div class="dash-panel-header">
                            <h5 class="dash-panel-title"><i class="mdi mdi-certificate me-2"></i>Certificates</h5>
                        </div>
                        <div class="dash-cert-stats">
                            <div class="dash-cert-stat"><span class="dash-cert-num">{{ data.certStats?.today || 0 }}</span><span class="dash-cert-label">Today</span></div>
                            <div class="dash-cert-stat"><span class="dash-cert-num">{{ data.certStats?.week || 0 }}</span><span class="dash-cert-label">This Week</span></div>
                            <div class="dash-cert-stat"><span class="dash-cert-num">{{ data.certStats?.month || 0 }}</span><span class="dash-cert-label">This Month</span></div>
                        </div>
                        <canvas ref="certChart" height="120" class="mt-3"></canvas>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="dash-panel dash-panel-activities">
                        <div class="dash-panel-header">
                            <h5 class="dash-panel-title"><i class="mdi mdi-history me-2"></i>Recent Activities</h5>
                            <span class="dash-panel-badge">{{ data.recentActivities?.length || 0 }} Activities</span>
                        </div>
                        <div class="dash-activity-list">
                            <div v-if="!data.recentActivities?.length" class="dash-activity-empty">
                                <i class="mdi mdi-history" style="font-size:36px; color:#c4c9d4;"></i>
                                <p class="text-muted mt-2 mb-0">No activities recorded yet.</p>
                            </div>
                            <div v-for="(act, i) in data.recentActivities" :key="i" class="dash-activity-item">
                                <div class="dash-activity-avatar">{{ act.user?.charAt(0)?.toUpperCase() || 'A' }}</div>
                                <div class="dash-activity-info">
                                    <div class="dash-activity-desc"><strong>{{ act.user }}</strong> {{ act.description }}</div>
                                    <div class="dash-activity-meta">
                                        <span class="dash-activity-module">{{ act.module }}</span>
                                        <span class="dash-activity-time"><i class="mdi mdi-clock-outline me-1"></i>{{ act.time }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </template>

        <!-- ADD TASK MODAL -->
        <div v-if="showAddTask" class="vue-modal-overlay" @click.self="showAddTask = false">
            <div class="vue-modal-content leaders-modal">
                <div class="leaders-modal-bar"></div>
                <div class="vue-modal-header">
                    <h6 class="fw-bold mb-0">Add Task</h6>
                    <button class="btn-close" @click="showAddTask = false"></button>
                </div>
                <div class="vue-modal-body">
                    <input type="text" v-model="newTask.name" class="form-control mb-3" placeholder="Task name..." style="border-radius:10px; height:42px;">
                    <input type="date" v-model="newTask.due_date" class="form-control mb-3" style="border-radius:10px; height:42px;">
                    <button class="btn btn-outline-success w-100" @click="addTask">Save Task</button>
                </div>
            </div>
        </div>

        <!-- UPDATE STATUS MODAL -->
        <div v-if="showUpdateStatus" class="vue-modal-overlay" @click.self="showUpdateStatus = false">
            <div class="vue-modal-content leaders-modal">
                <div class="leaders-modal-bar" style="background: linear-gradient(to right, #2449d8, #5c7cfa);"></div>
                <div class="vue-modal-header">
                    <h6 class="fw-bold mb-0">Update Task Status</h6>
                    <button class="btn-close" @click="showUpdateStatus = false"></button>
                </div>
                <div class="vue-modal-body">
                    <label class="form-label fw-semibold" style="font-size:13px;">Select Date</label>
                    <input type="date" v-model="statusForm.date" class="form-control mb-3" style="border-radius:10px; height:42px;" @change="loadTasksByDate">

                    <label class="form-label fw-semibold" style="font-size:13px;">Select Task</label>
                    <select v-model="statusForm.task_id" class="form-select mb-3" style="border-radius:10px; height:42px;" :disabled="!dateTasks.length">
                        <option value="">— Choose task —</option>
                        <option v-for="t in dateTasks" :key="t.id" :value="t.id">{{ t.name }} ({{ t.status }})</option>
                    </select>

                    <label class="form-label fw-semibold" style="font-size:13px;">New Status</label>
                    <select v-model="statusForm.status" class="form-select mb-3" style="border-radius:10px; height:42px;">
                        <option value="pending">Pending</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                    </select>

                    <button class="btn btn-outline-primary w-100" @click="updateStatus">Save Status</button>
                </div>
            </div>
        </div>

        <!-- DELETE CONFIRM MODAL -->
        <div v-if="showDeleteConfirm" class="vue-modal-overlay" @click.self="showDeleteConfirm = false">
            <div class="vue-modal-content leaders-modal" style="max-width:360px;">
                <div class="leaders-modal-bar" style="background: linear-gradient(to right, #e53935, #ff6b6b);"></div>
                <div class="vue-modal-body text-center" style="padding:28px;">
                    <i class="mdi mdi-alert-circle-outline" style="font-size:48px; color:#e53935;"></i>
                    <h5 class="fw-bold mt-3 mb-2">Delete Task</h5>
                    <p class="text-muted mb-4" style="font-size:14px;">Are you sure? This action cannot be undone.</p>
                    <div class="d-flex gap-2 justify-content-center">
                        <button class="btn btn-outline-secondary btn-sm px-4" @click="showDeleteConfirm = false">Cancel</button>
                        <button class="btn btn-danger btn-sm px-4" @click="confirmDelete">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Chart from 'chart.js/auto';

export default {
    name: 'Dashboard',
    data() {
        return {
            loading: true,
            data: {},
            stats: [],
            animatedValues: [],
            tasks: [],
            // Modals
            showAddTask: false,
            showUpdateStatus: false,
            showDeleteConfirm: false,
            deleteTargetId: null,
            // Forms
            newTask: { name: '', due_date: new Date().toISOString().split('T')[0] },
            statusForm: { date: new Date().toISOString().split('T')[0], task_id: '', status: 'pending' },
            dateTasks: [],
        };
    },
    computed: {
        csrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.content || '';
        },
    },
    async mounted() {
        try {
            await this.fetchData();
        } catch (e) {
            console.error('Dashboard fetch failed:', e);
        }
        this.loading = false;
        this.$nextTick(() => {
            this.initCharts();
            this.animateCounters();
        });
    },
    methods: {
        capitalize(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : ''; },

        async fetchData() {
            const res = await fetch('/api/dashboard-data');
            this.data = await res.json();

            const gradients = [
                'linear-gradient(135deg, #667eea, #764ba2)',
                'linear-gradient(135deg, #11998e, #38ef7d)',
                'linear-gradient(135deg, #f093fb, #f5576c)',
                'linear-gradient(135deg, #7f53ac, #647dee)',
                'linear-gradient(135deg, #4facfe, #00f2fe)',
                'linear-gradient(135deg, #fa709a, #fee140)',
            ];
            this.stats = this.data.stats.map((s, i) => ({ ...s, gradient: gradients[i] }));
            this.animatedValues = this.stats.map(() => 0);
            this.tasks = this.data.tasks || [];
        },

        animateCounters() {
            this.stats.forEach((stat, i) => {
                const target = stat.value;
                const duration = 1200;
                const start = performance.now();
                const step = (now) => {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    this.animatedValues[i] = Math.floor(eased * target);
                    if (progress < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            });
        },

        initCharts() {
            if (this.$refs.statusChart) {
                const ms = this.data.membershipStatus;
                new Chart(this.$refs.statusChart, {
                    type: 'doughnut',
                    data: { labels: ['Baptized', 'Dedicated', 'N/A'], datasets: [{ data: [ms.baptized, ms.dedicated, ms.na], backgroundColor: ['#28a745', '#2449d8', '#8898aa'], borderWidth: 0 }] },
                    options: { responsive: true, cutout: '70%', plugins: { legend: { display: false } } }
                });
            }
            if (this.$refs.certChart) {
                new Chart(this.$refs.certChart, {
                    type: 'bar',
                    data: { labels: this.data.certTrend.labels, datasets: [{ data: this.data.certTrend.data, backgroundColor: 'rgba(40,167,69,0.7)', borderRadius: 6, borderSkipped: false }] },
                    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true }, x: { grid: { display: false } } } }
                });
            }
        },

        // ===== TASK ACTIONS =====
        async toggleTask(task) {
            const res = await fetch('/task/toggle/' + task.id, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken }
            });
            const d = await res.json();
            if (d.success) {
                task.completed = d.task.completed;
                task.status = d.task.status;
            }
        },

        deleteTask(task) {
            this.deleteTargetId = task.id;
            this.showDeleteConfirm = true;
        },

        async confirmDelete() {
            const res = await fetch('/task/delete/' + this.deleteTargetId, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': this.csrfToken }
            });
            const d = await res.json();
            if (d.success) {
                this.tasks = this.tasks.filter(t => t.id !== this.deleteTargetId);
            }
            this.showDeleteConfirm = false;
            this.deleteTargetId = null;
        },

        async addTask() {
            if (!this.newTask.name.trim()) return;
            const res = await fetch('/task/store', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken },
                body: JSON.stringify(this.newTask)
            });
            const d = await res.json();
            if (d.success) {
                this.tasks.unshift({
                    id: d.task.id,
                    name: d.task.name,
                    status: d.task.status || 'pending',
                    completed: d.task.completed || false,
                    due_date: d.task.due_date,
                });
                this.newTask = { name: '', due_date: new Date().toISOString().split('T')[0] };
                this.showAddTask = false;
            }
        },

        async loadTasksByDate() {
            if (!this.statusForm.date) { this.dateTasks = []; return; }
            const res = await fetch('/task/by-date?date=' + this.statusForm.date);
            this.dateTasks = await res.json();
        },

        async updateStatus() {
            if (!this.statusForm.task_id) return;
            const res = await fetch('/task/update-status', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': this.csrfToken },
                body: JSON.stringify({ task_id: this.statusForm.task_id, status: this.statusForm.status })
            });
            const d = await res.json();
            if (d.success) {
                const task = this.tasks.find(t => t.id == this.statusForm.task_id);
                if (task) {
                    task.status = this.statusForm.status;
                    task.completed = this.statusForm.status === 'completed';
                }
                this.showUpdateStatus = false;
                this.statusForm = { date: new Date().toISOString().split('T')[0], task_id: '', status: 'pending' };
                this.dateTasks = [];
            }
        },
    }
};
</script>

<style scoped>
.dash-loading { padding: 20px 0; }
.dash-skeleton { background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 16px; }
.dash-skeleton-card { height: 130px; }
.dash-skeleton-chart { height: 300px; }
@keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

/* Vue Modal Overlay */
.vue-modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.45); display: flex; align-items: center; justify-content: center; z-index: 1060; animation: fadeIn .2s; }
.vue-modal-content { width: 100%; max-width: 420px; border-radius: 18px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.15); animation: popIn .3s cubic-bezier(0.175,0.885,0.32,1.2); background: #fff; }
.vue-modal-header { display: flex; align-items: center; justify-content: space-between; padding: 18px 20px 0; }
.vue-modal-body { padding: 16px 20px 20px; }
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes popIn { from { transform: scale(0.8) translateY(20px); opacity: 0; } to { transform: scale(1) translateY(0); opacity: 1; } }
</style>
