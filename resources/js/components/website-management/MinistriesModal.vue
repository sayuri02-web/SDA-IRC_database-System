<template>
<Transition name="wm-modal">
<div v-if="show" class="wm-modal-overlay" @click.self="close">
<div class="wm-modal-content wm-modal-xl">
    <div class="wm-modal-header">
        <div class="wm-modal-title"><i class="mdi mdi-account-group"></i><span>Ministries</span></div>
        <div class="wm-modal-header-actions">
            <div class="wm-modal-search-wrap">
                <i class="mdi mdi-magnify"></i>
                <input type="text" v-model="search" @input="fetchMinistries" placeholder="Search ministries...">
            </div>
            <button class="wm-modal-add-btn" @click="openForm(null)"><i class="mdi mdi-plus"></i> Add Ministry</button>
            <button class="wm-modal-close" @click="close"><i class="mdi mdi-close"></i></button>
        </div>
    </div>
    <div class="wm-modal-body">
        <div v-if="loading" class="wm-modal-loading"><div class="wm-spinner"></div> Loading...</div>
        <div v-else-if="ministries.length === 0" class="wm-modal-empty"><i class="mdi mdi-account-group-outline"></i><p>No ministries yet. Click "Add Ministry" to create one.</p></div>
        <table v-else class="wm-modal-table">
            <thead><tr><th style="width:60px">Icon</th><th>Ministry Name</th><th>Status</th><th>Updated</th><th class="text-center">Actions</th></tr></thead>
            <tbody>
                <tr v-for="m in ministries" :key="m.id">
                    <td><div class="wm-ministry-icon-cell"><i :class="'mdi ' + m.icon"></i></div></td>
                    <td class="fw-600">{{ m.name }}</td>
                    <td><span :class="['wm-badge', m.is_published ? 'wm-badge-green' : 'wm-badge-gray']">{{ m.is_published ? 'Published' : 'Draft' }}</span></td>
                    <td class="text-muted">{{ m.updated_at }}</td>
                    <td class="text-center">
                        <button class="wm-action-btn wm-action-edit" @click="openForm(m)" title="Edit"><i class="mdi mdi-pencil-outline"></i></button>
                        <button class="wm-action-btn wm-action-toggle" @click="toggle(m)" :title="m.is_published ? 'Unpublish' : 'Publish'"><i :class="m.is_published ? 'mdi mdi-eye-off-outline' : 'mdi mdi-eye-outline'"></i></button>
                        <button class="wm-action-btn wm-action-delete" @click="destroy(m)" title="Delete"><i class="mdi mdi-delete-outline"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
</Transition>
<MinistryFormModal ref="formModal" @saved="fetchMinistries" />
</template>

<script>
import MinistryFormModal from './MinistryFormModal.vue';
export default {
    name: 'MinistriesModal',
    components: { MinistryFormModal },
    data() { return { show: false, ministries: [], search: '', loading: false, debounce: null }; },
    methods: {
        open() { this.show = true; this.search = ''; document.body.style.overflow = 'hidden'; this.fetchMinistries(); },
        close() { this.show = false; document.body.style.overflow = ''; },
        openForm(item) { this.$refs.formModal.open(item); },
        fetchMinistries() {
            clearTimeout(this.debounce);
            this.debounce = setTimeout(async () => {
                this.loading = true;
                try {
                    const res = await fetch('/website-management/ministries/list?search=' + encodeURIComponent(this.search));
                    this.ministries = await res.json();
                } catch(e) { this.ministries = []; }
                this.loading = false;
            }, 200);
        },
        async toggle(m) {
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            await fetch('/website-management/ministries/' + m.id + '/toggle', { method: 'POST', headers: { 'X-CSRF-TOKEN': token } });
            m.is_published = !m.is_published;
            if (window.toast) window.toast.success('Updated', m.is_published ? 'Ministry published' : 'Ministry unpublished');
        },
        async destroy(m) {
            if (!confirm('Delete "' + m.name + '"? This cannot be undone.')) return;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            await fetch('/website-management/ministries/' + m.id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': token } });
            this.ministries = this.ministries.filter(x => x.id !== m.id);
            if (window.toast) window.toast.success('Deleted', 'Ministry removed successfully');
        }
    },
    mounted() {
        window.addEventListener('open-ministries-modal', () => this.open());
        document.addEventListener('keydown', (ev) => { if (ev.key === 'Escape' && this.show) this.close(); });
    }
};
</script>
