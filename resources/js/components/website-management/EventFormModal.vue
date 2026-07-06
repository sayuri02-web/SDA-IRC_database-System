<template>
<Transition name="wm-modal">
<div v-if="show" class="wm-modal-overlay" @click.self="close" style="z-index:10000">
<div class="wm-modal-content wm-modal-md">
    <div class="wm-modal-header">
        <div class="wm-modal-title"><i class="mdi mdi-calendar-plus"></i><span>{{ editing ? 'Edit Event' : 'Add Event' }}</span></div>
        <button class="wm-modal-close" @click="close"><i class="mdi mdi-close"></i></button>
    </div>
    <div class="wm-modal-body wm-form-body">
        <div class="wm-form-field">
            <label>Title <span class="text-danger">*</span></label>
            <input type="text" v-model="form.title" placeholder="Event title">
        </div>
        <div class="wm-form-field">
            <label>Description</label>
            <textarea v-model="form.description" rows="4" placeholder="Event description..."></textarea>
        </div>
        <div class="wm-form-row">
            <div class="wm-form-field">
                <label>Event Date</label>
                <input type="date" v-model="form.event_date">
            </div>
            <div class="wm-form-field">
                <label>Event Time</label>
                <input type="text" v-model="form.event_time" placeholder="e.g. 9:00 AM - 12:00 PM">
            </div>
        </div>
        <div class="wm-form-field">
            <label>Location</label>
            <input type="text" v-model="form.location" placeholder="Event location">
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
            <span v-else><i class="mdi mdi-content-save-outline me-1"></i>{{ editing ? 'Update' : 'Save Event' }}</span>
        </button>
    </div>
</div>
</div>
</Transition>
</template>

<script>
export default {
    name: 'EventFormModal',
    emits: ['saved'],
    data() { return { show: false, editing: false, editId: null, saving: false, error: null, form: this.emptyForm() }; },
    methods: {
        emptyForm() { return { title: '', description: '', event_date: '', event_time: '', location: '', is_published: false }; },
        open(event) {
            if (event) { this.editing = true; this.editId = event.id; this.form = { ...event }; }
            else { this.editing = false; this.editId = null; this.form = this.emptyForm(); }
            this.error = null; this.show = true;
        },
        close() { this.show = false; },
        async save() {
            if (!this.form.title.trim()) { this.error = 'Title is required.'; return; }
            this.saving = true; this.error = null;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const url = this.editing ? '/website-management/events/' + this.editId : '/website-management/events';
            const method = this.editing ? 'PUT' : 'POST';
            try {
                const res = await fetch(url, { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token }, body: JSON.stringify(this.form) });
                if (!res.ok) { const d = await res.json().catch(() => null); this.error = d?.message || 'Failed to save.'; this.saving = false; return; }
                this.$emit('saved');
                this.close();
                if (window.toast) window.toast.success('Saved', this.editing ? 'Event updated' : 'Event created');
            } catch(e) { this.error = 'Network error.'; }
            this.saving = false;
        }
    }
};
</script>
