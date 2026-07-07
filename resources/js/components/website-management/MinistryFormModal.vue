<template>
<Transition name="wm-modal">
<div v-if="show" class="wm-modal-overlay" @click.self="close" style="z-index:10000">
<div class="wm-modal-content wm-modal-md">
    <div class="wm-modal-header">
        <div class="wm-modal-title"><i class="mdi mdi-account-group"></i><span>{{ editing ? 'Edit Ministry' : 'Add Ministry' }}</span></div>
        <button class="wm-modal-close" @click="close"><i class="mdi mdi-close"></i></button>
    </div>
    <div class="wm-modal-body wm-form-body">
        <div class="wm-form-field">
            <label>Ministry Name <span class="text-danger">*</span></label>
            <input type="text" v-model="form.name" placeholder="e.g. Youth Ministry">
        </div>
        <div class="wm-form-field">
            <label>Description</label>
            <textarea v-model="form.description" rows="4" placeholder="Brief description of the ministry..."></textarea>
        </div>
        <div class="wm-form-field">
            <label>Icon</label>
            <div class="wm-icon-preview">
                <div class="wm-icon-preview-box"><i :class="'mdi ' + form.icon"></i></div>
                <span>{{ form.icon }}</span>
            </div>
            <div class="wm-icon-grid">
                <button
                    v-for="ic in icons" :key="ic"
                    type="button"
                    :class="['wm-icon-option', { 'wm-icon-active': form.icon === ic }]"
                    @click="form.icon = ic"
                    :title="ic">
                    <i :class="'mdi ' + ic"></i>
                </button>
            </div>
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
            <span v-else><i class="mdi mdi-content-save-outline me-1"></i>{{ editing ? 'Update' : 'Save Ministry' }}</span>
        </button>
    </div>
</div>
</div>
</Transition>
</template>

<script>
export default {
    name: 'MinistryFormModal',
    emits: ['saved'],
    data() {
        return {
            show: false,
            editing: false,
            editId: null,
            saving: false,
            error: null,
            form: this.emptyForm(),
            icons: [
                'mdi-account-group',
                'mdi-compass-outline',
                'mdi-star-outline',
                'mdi-heart-outline',
                'mdi-shield-account',
                'mdi-music-note',
                'mdi-home-heart',
                'mdi-book-open-variant',
                'mdi-cross',
                'mdi-hands-pray',
                'mdi-school',
                'mdi-human-male-female-child',
                'mdi-microphone',
                'mdi-church',
                'mdi-earth',
                'mdi-hand-heart',
                'mdi-campfire',
                'mdi-food-apple',
                'mdi-hospital-box',
                'mdi-palette',
            ]
        };
    },
    methods: {
        emptyForm() { return { name: '', description: '', icon: 'mdi-account-group', is_published: false }; },
        open(item) {
            if (item) {
                this.editing = true;
                this.editId = item.id;
                this.form = { name: item.name, description: item.description || '', icon: item.icon || 'mdi-account-group', is_published: item.is_published };
            } else {
                this.editing = false;
                this.editId = null;
                this.form = this.emptyForm();
            }
            this.error = null;
            this.show = true;
        },
        close() { this.show = false; },
        async save() {
            if (!this.form.name.trim()) { this.error = 'Ministry name is required.'; return; }
            this.saving = true; this.error = null;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const url = this.editing ? '/website-management/ministries/' + this.editId : '/website-management/ministries';
            const method = this.editing ? 'PUT' : 'POST';
            try {
                const res = await fetch(url, {
                    method,
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
                    body: JSON.stringify(this.form)
                });
                if (!res.ok) {
                    const d = await res.json().catch(() => null);
                    this.error = d?.message || 'Failed to save.';
                    this.saving = false;
                    return;
                }
                this.$emit('saved');
                this.close();
                if (window.toast) window.toast.success('Saved', this.editing ? 'Ministry updated' : 'Ministry created');
            } catch(e) { this.error = 'Network error.'; }
            this.saving = false;
        }
    }
};
</script>
