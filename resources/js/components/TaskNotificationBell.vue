<template>
<div class="task-notif-bell" ref="bellWrapper">
    <button class="task-notif-btn" @click="toggleDropdown" type="button">
        <i class="mdi mdi-bell-outline"></i>
        <span v-if="unreadCount > 0" class="task-notif-badge">{{ unreadCount > 99 ? '99+' : unreadCount }}</span>
    </button>

    <Transition name="notif-drop">
    <div v-if="showDropdown" class="task-notif-dropdown">
        <div class="task-notif-header">
            <span>Quick Task Notifications</span>
            <button v-if="unreadCount > 0" class="task-notif-mark-all" @click="markAllRead">Mark all read</button>
        </div>
        <div class="task-notif-list">
            <div v-if="notifications.length === 0" class="task-notif-empty">
                <i class="mdi mdi-bell-check-outline"></i>
                <p>No notifications</p>
            </div>
            <div
                v-for="n in notifications"
                :key="n.id"
                class="task-notif-item"
                :class="{ 'task-notif-unread': !n.read_at }"
                @click="markRead(n)"
            >
                <div class="task-notif-icon" :class="'task-notif-icon-' + n.type">
                    <i :class="'mdi ' + getIcon(n.type)"></i>
                </div>
                <div class="task-notif-content">
                    <div class="task-notif-msg">{{ n.message }}</div>
                    <div class="task-notif-time"><i class="mdi mdi-clock-outline"></i> {{ n.time_ago }}</div>
                </div>
            </div>
        </div>
    </div>
    </Transition>
</div>
</template>

<script>
export default {
    name: 'TaskNotificationBell',
    data() {
        return {
            showDropdown: false,
            notifications: [],
            unreadCount: 0,
            pollInterval: null,
        };
    },
    methods: {
        getIcon(type) {
            if (type === 'reminder') return 'mdi-bell-ring-outline';
            if (type === 'overdue') return 'mdi-alert-circle-outline';
            if (type === 'completed_late') return 'mdi-clock-alert-outline';
            return 'mdi-bell-outline';
        },
        toggleDropdown() {
            this.showDropdown = !this.showDropdown;
            if (this.showDropdown) {
                this.fetchNotifications();
            }
        },
        async fetchNotifications() {
            try {
                const res = await fetch('/task-notifications');
                const data = await res.json();
                this.notifications = data.notifications || [];
                this.unreadCount = data.unread_count || 0;
            } catch (e) {
                console.error('Failed to fetch notifications:', e);
            }
        },
        async fetchUnreadCount() {
            try {
                const res = await fetch('/task-notifications/unread-count');
                const data = await res.json();
                this.unreadCount = data.unread_count || 0;
            } catch (e) {
                // silent
            }
        },
        async markAllRead() {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            try {
                await fetch('/task-notifications/mark-all-read', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                });
                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                this.unreadCount = 0;
            } catch (e) {
                console.error('Failed to mark all read:', e);
            }
        },
        async markRead(n) {
            if (n.read_at) return;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            try {
                await fetch('/task-notifications/' + n.id + '/mark-read', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                });
                n.read_at = new Date().toISOString();
                this.unreadCount = Math.max(0, this.unreadCount - 1);
            } catch (e) {
                // silent
            }
        },
        handleClickOutside(e) {
            if (this.$refs.bellWrapper && !this.$refs.bellWrapper.contains(e.target)) {
                this.showDropdown = false;
            }
        }
    },
    mounted() {
        this.fetchUnreadCount();
        // Poll every 60 seconds for new notifications
        this.pollInterval = setInterval(() => this.fetchUnreadCount(), 60000);
        document.addEventListener('click', this.handleClickOutside);
    },
    beforeUnmount() {
        if (this.pollInterval) clearInterval(this.pollInterval);
        document.removeEventListener('click', this.handleClickOutside);
    }
};
</script>
