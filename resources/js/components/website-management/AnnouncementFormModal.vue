<template>
<Transition name="wm-modal">
<div v-if="show" class="wm-modal-overlay" @click.self="close" style="z-index:10000">
<div class="wm-modal-content wm-modal-md">
    <div class="wm-modal-header">
        <div class="wm-modal-title"><i class="mdi mdi-bullhorn-outline"></i><span>{{ editing ? 'Edit Announcement' : 'Add Announcement' }}</span></div>
        <button class="wm-modal-close" @click="close"><i class="mdi mdi-close"></i></button>
    </div>
    <div class="wm-modal-body wm-form-body">
        <div class="wm-form-field">
            <label>Title <span class="text-danger">*</span></label>
            <input type="text" v-model="form.title" placeholder="Announcement title">
        </div>
        <div class="wm-form-field">
            <label>Description</label>
            <textarea v-model="form.description" rows="4" placeholder="Announcement details..."></textarea>
        </div>
        <div class="wm-form-field">
            <label>Announcement Date <span class="text-danger">*</span></label>
            <input type="date" v-model="form.announcement_date">
        </div>
        <div class="wm-form-field">
            <label>Location <span class="text-danger">*</span></label>
            <input type="text" v-model="form.location" placeholder="Enter announcement location">
        </div>
        <div class="wm-form-field">
            <label>Status</label>
            <select v-model="form.is_published">
                <option :value="false">Draft</option>
                <option :value="true">Published</option>
            </select>
        </div>
        <div v-if="error" class="wm-form-error">{{ error }}</div>
    </div>
    <div class="wm-modal-footer">
        <button class="btn btn-outline-secondary" @click="close">Cancel</button>
        <button class="btn btn-success" @click="save" :disabled="saving">
            <span v-if="saving"><i class="mdi mdi-loading mdi-spin"></i></span>
            <span v-else><i class="mdi mdi-content-save-outline me-1"></i>{{ editing ? 'Update' : 'Save Announcement' }}</span>
        </button>
    </div>
</div>
</div>
</Transition>
</template>

<script>
export default {
    name: 'AnnouncementFormModal',
    emits: ['saved'],
    data() { return { show: false, editing: false, editId: null, saving: false, error: null, form: this.emptyForm() }; },
    methods: {
        emptyForm() { return { title: '', description: '', announcement_date: '', location: '', is_published: false }; },
        open(item) {
            if (item) {
                this.editing = true;
                this.editId = item.id;
                this.form = { title: item.title, description: item.description, announcement_date: item.announcement_date || '', location: item.location || '', is_published: item.is_published };
            } else {
                this.editing = false;
                this.editId = null;
                this.form = this.emptyForm();
            }
            this.error = null; this.show = true;
        },
        close() { this.show = false; },
        async save() {
            if (!this.form.title.trim()) { this.error = 'Title is required.'; return; }
            if (!this.form.announcement_date) { this.error = 'Announcement date is required.'; return; }
            if (!this.form.location || !this.form.location.trim()) { this.error = 'Location is required.'; return; }
            this.saving = true; this.error = null;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const url = this.editing ? '/website-management/announcements/' + this.editId : '/website-management/announcements';
            const method = this.editing ? 'PUT' : 'POST';
            try {
                const res = await fetch(url, { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }, body: JSON.stringify(this.form) });
                if (!res.ok) { const d = await res.json().catch(() => null); this.error = d?.message || 'Failed to save.'; this.saving = false; return; }
                this.$emit('saved');
                this.close();
                if (window.toast) window.toast.success('Saved', this.editing ? 'Announcement updated' : 'Announcement created');
            } catch(e) { this.error = 'Network error.'; }
            this.saving = false;
        }
    }
};
</script>
