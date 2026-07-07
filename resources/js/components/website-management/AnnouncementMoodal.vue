<template>
<Transition name="wm-modal">
<div v-if="show" class="wm-modal-overlay" @click.self="close">
<div class="wm-modal-content wm-modal-xl">
    <div class="wm-modal-header">
        <div class="wm-modal-title"><i class="mdi mdi-bullhorn-outline"></i><span>Announcements</span></div>
        <div class="wm-modal-header-actions">
            <div class="wm-modal-search-wrap">
                <i class="mdi mdi-magnify"></i>
                <input type="text" v-model="search" @input="fetchAnnouncements" placeholder="Search announcements...">
            </div>
            <button class="wm-modal-add-btn" @click="addAction"><i class="mdi mdi-plus"></i> Add Announcement</button>
            <button class="wm-modal-close" @click="close"><i class="mdi mdi-close"></i></button>
        </div>
    </div>
    <div class="wm-modal-body">
        <div v-if="loading" class="wm-modal-loading"><div class="wm-spinner"></div> Loading...</div>
        <div v-else-if="announcements.length === 0" class="wm-modal-empty"><i class="mdi mdi-bullhorn-variant-outline"></i><p>No announcements yet. Click "Add Announcement" to create one.</p></div>
        <table v-else class="wm-modal-table">
            <thead><tr><th>Title</th><th>Status</th><th>Updated</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
                <tr v-for="a in announcements" :key="a.id">
                    <td class="fw-600">{{ a.title }}</td>
                    <td><span :class="['wm-badge', a.is_published ? 'wm-badge-green' : 'wm-badge-gray']">{{ a.is_published ? 'Published' : 'Draft' }}</span></td>
                    <td class="text-muted">{{ a.updated_at }}</td>
                    <td class="text-center">
                        <button class="wm-action-btn wm-action-edit" @click="editAction(a)" title="Edit"><i class="mdi mdi-pencil-outline"></i></button>
                        <button class="wm-action-btn wm-action-toggle" @click="toggleAction(a)" :title="a.is_published ? 'Unpublish' : 'Publish'"><i :class="a.is_published ? 'mdi mdi-eye-off-outline' : 'mdi mdi-eye-outline'"></i></button>
                        <button class="wm-action-btn wm-action-delete" @click="destroyAction(a)" title="Delete"><i class="mdi mdi-delete-outline"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
</Transition>
<AnnouncementFormModal ref="formModal" @saved="fetchAnnouncements" />
</template>

<script>
import AnnouncementFormModal from './AnnouncementFormModal.vue';
export default {
    name: 'AnnouncementsModal',
    components: { AnnouncementFormModal },
    data() { return { show: false, announcements: [], search: '', loading: false, debounce: null }; },
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
        editAction(a) { if (this.checkPerm()) this.openForm(a); },
        toggleAction(a) { if (this.checkPerm()) this.toggle(a); },
        destroyAction(a) { if (this.checkPerm()) this.destroy(a); },
        open() { this.show = true; this.search = ''; document.body.style.overflow = 'hidden'; this.fetchAnnouncements(); },
        close() { this.show = false; document.body.style.overflow = ''; },
        openForm(item) { this.$refs.formModal.open(item); },
        fetchAnnouncements() {
            clearTimeout(this.debounce);
            this.debounce = setTimeout(async () => {
                this.loading = true;
                try {
                    const res = await fetch('/website-management/announcements/list?search=' + encodeURIComponent(this.search));
                    this.announcements = await res.json();
                } catch(e) { this.announcements = []; }
                this.loading = false;
            }, 200);
        },
        async toggle(a) {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            await fetch('/website-management/announcements/' + a.id + '/toggle', { method: 'POST', headers: { 'X-CSRF-TOKEN': token } });
            a.is_published = !a.is_published;
            if (window.toast) window.toast.success('Updated', a.is_published ? 'Announcement published' : 'Announcement unpublished');
        },
        async destroy(a) {
            if (!confirm('Delete "' + a.title + '"? This cannot be undone.')) return;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            await fetch('/website-management/announcements/' + a.id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': token } });
            this.announcements = this.announcements.filter(x => x.id !== a.id);
            if (window.toast) window.toast.success('Deleted', 'Announcement removed successfully');
        }
    },
    mounted() {
        window.addEventListener('open-announcements-modal', () => this.open());
        document.addEventListener('keydown', (ev) => { if (ev.key === 'Escape' && this.show) this.close(); });
    }
};
</script>
