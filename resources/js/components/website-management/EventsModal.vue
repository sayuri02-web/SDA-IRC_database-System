<template>
<Transition name="wm-modal">
<div v-if="show" class="wm-modal-overlay" @click.self="close">
<div class="wm-modal-content wm-modal-xl">
    <div class="wm-modal-header">
        <div class="wm-modal-title"><i class="mdi mdi-calendar-month"></i><span>Events</span></div>
        <div class="wm-modal-header-actions">
            <div class="wm-modal-search-wrap">
                <i class="mdi mdi-magnify"></i>
                <input type="text" v-model="search" @input="fetchEvents" placeholder="Search events...">
            </div>
            <button class="wm-modal-add-btn" @click="addAction"><i class="mdi mdi-plus"></i> Add Event</button>
            <button class="wm-modal-close" @click="close"><i class="mdi mdi-close"></i></button>
        </div>
    </div>
    <div class="wm-modal-body">
        <div v-if="loading" class="wm-modal-loading"><div class="wm-spinner"></div> Loading...</div>
        <div v-else-if="events.length === 0" class="wm-modal-empty"><i class="mdi mdi-calendar-blank-outline"></i><p>No events yet. Click "Add Event" to create one.</p></div>
        <table v-else class="wm-modal-table">
            <thead><tr><th>Title</th><th>Date</th><th>Time</th><th>Location</th><th>Status</th><th>Updated</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
                <tr v-for="e in events" :key="e.id">
                    <td class="fw-600">{{ e.title }}</td>
                    <td>{{ e.event_date || '—' }}</td>
                    <td>{{ e.event_time || '—' }}</td>
                    <td>{{ e.location || '—' }}</td>
                    <td><span :class="['wm-badge', e.is_published ? 'wm-badge-green' : 'wm-badge-gray']">{{ e.is_published ? 'Published' : 'Draft' }}</span></td>
                    <td class="text-muted">{{ e.updated_at }}</td>
                    <td class="text-center">
                        <button class="wm-action-btn wm-action-edit" @click="editAction(e)" title="Edit"><i class="mdi mdi-pencil-outline"></i></button>
                        <button class="wm-action-btn wm-action-toggle" @click="toggleAction(e)" :title="e.is_published ? 'Unpublish' : 'Publish'"><i :class="e.is_published ? 'mdi mdi-eye-off-outline' : 'mdi mdi-eye-outline'"></i></button>
                        <button class="wm-action-btn wm-action-delete" @click="destroyAction(e)" title="Delete"><i class="mdi mdi-delete-outline"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
</Transition>
<EventFormModal ref="formModal" @saved="fetchEvents" />
</template>

<script>
import EventFormModal from './EventFormModal.vue';
export default {
    name: 'EventsModal',
    components: { EventFormModal },
    data() { return { show: false, events: [], search: '', loading: false, debounce: null }; },
    computed: {
        canManage() { return window.userPermissions && window.userPermissions.canManage('website-management'); }
    },
    methods: {
        checkPerm() {
            if (this.canManage) return true;
            window.dispatchEvent(new CustomEvent('show-access-denied', { detail: { module: 'website-management' } }));
            return false;
        },
        addAction() { if (this.checkPerm()) this.openForm(null); },
        editAction(e) { if (this.checkPerm()) this.openForm(e); },
        toggleAction(e) { if (this.checkPerm()) this.toggle(e); },
        destroyAction(e) { if (this.checkPerm()) this.destroy(e); },
        open() { this.show = true; this.search = ''; document.body.style.overflow = 'hidden'; this.fetchEvents(); },
        close() { this.show = false; document.body.style.overflow = ''; },
        openForm(event) { this.$refs.formModal.open(event); },
        fetchEvents() {
            clearTimeout(this.debounce);
            this.debounce = setTimeout(async () => {
                this.loading = true;
                try {
                    const res = await fetch('/website-management/events/list?search=' + encodeURIComponent(this.search));
                    this.events = await res.json();
                } catch(e) { this.events = []; }
                this.loading = false;
            }, 200);
        },
        async toggle(e) {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            await fetch('/website-management/events/' + e.id + '/toggle', { method: 'POST', headers: { 'X-CSRF-TOKEN': token } });
            e.is_published = !e.is_published;
            if (window.toast) window.toast.success('Updated', e.is_published ? 'Event published' : 'Event unpublished');
        },
        async destroy(e) {
            if (!confirm('Delete "' + e.title + '"? This cannot be undone.')) return;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            await fetch('/website-management/events/' + e.id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': token } });
            this.events = this.events.filter(x => x.id !== e.id);
            if (window.toast) window.toast.success('Deleted', 'Event removed successfully');
        }
    },
    mounted() {
        window.addEventListener('open-events-modal', () => this.open());
        document.addEventListener('keydown', (ev) => { if (ev.key === 'Escape' && this.show) this.close(); });
    }
};
</script>
