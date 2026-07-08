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
            <label>Event Icon <span class="text-danger">*</span></label>
            <div class="icon-picker">
                <div class="icon-picker-preview" @click="iconPickerOpen = !iconPickerOpen">
                    <i :class="'mdi ' + form.icon" class="icon-picker-selected"></i>
                    <span class="icon-picker-label">{{ form.icon || 'Select an icon' }}</span>
                    <i class="mdi mdi-chevron-down icon-picker-chevron"></i>
                </div>
                <div v-if="iconPickerOpen" class="icon-picker-dropdown">
                    <div class="icon-picker-search">
                        <i class="mdi mdi-magnify"></i>
                        <input type="text" v-model="iconSearch" placeholder="Search icons..." ref="iconSearchInput">
                    </div>
                    <div class="icon-picker-grid">
                        <button
                            v-for="icon in filteredIcons"
                            :key="icon.value"
                            type="button"
                            class="icon-picker-item"
                            :class="{ active: form.icon === icon.value }"
                            @click="selectIcon(icon.value)"
                            :title="icon.label"
                        >
                            <i :class="'mdi ' + icon.value"></i>
                        </button>
                    </div>
                </div>
            </div>
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
    data() {
        return {
            show: false,
            editing: false,
            editId: null,
            saving: false,
            error: null,
            form: this.emptyForm(),
            iconPickerOpen: false,
            iconSearch: '',
            availableIcons: [
                { value: 'mdi-calendar-star', label: 'Calendar Star' },
                { value: 'mdi-account-group', label: 'Group' },
                { value: 'mdi-book-open-page-variant', label: 'Bible Study' },
                { value: 'mdi-hand-heart', label: 'Outreach' },
                { value: 'mdi-music', label: 'Music' },
                { value: 'mdi-cross', label: 'Cross' },
                { value: 'mdi-home-heart', label: 'Home Heart' },
                { value: 'mdi-school', label: 'School' },
                { value: 'mdi-heart', label: 'Heart' },
                { value: 'mdi-lightbulb', label: 'Lightbulb' },
                { value: 'mdi-calendar-check', label: 'Calendar Check' },
                { value: 'mdi-microphone', label: 'Microphone' },
                { value: 'mdi-bullhorn', label: 'Bullhorn' },
                { value: 'mdi-food', label: 'Food' },
                { value: 'mdi-hiking', label: 'Hiking' },
                { value: 'mdi-tent', label: 'Camp' },
                { value: 'mdi-gift', label: 'Gift' },
                { value: 'mdi-church', label: 'Church' },
                { value: 'mdi-account-heart', label: 'Fellowship' },
                { value: 'mdi-pray', label: 'Prayer' },
                { value: 'mdi-candelabra', label: 'Candelabra' },
                { value: 'mdi-basketball', label: 'Sports' },
                { value: 'mdi-palette', label: 'Art' },
                { value: 'mdi-earth', label: 'Mission' },
                { value: 'mdi-human-handsup', label: 'Worship' },
                { value: 'mdi-baby-carriage', label: 'Dedication' },
                { value: 'mdi-water', label: 'Baptism' },
                { value: 'mdi-charity', label: 'Charity' },
                { value: 'mdi-run', label: 'Fun Run' },
                { value: 'mdi-flower', label: 'Flower' },
            ]
        };
    },
    computed: {
        filteredIcons() {
            if (!this.iconSearch) return this.availableIcons;
            const q = this.iconSearch.toLowerCase();
            return this.availableIcons.filter(i => i.label.toLowerCase().includes(q) || i.value.toLowerCase().includes(q));
        }
    },
    methods: {
        emptyForm() { return { title: '', icon: 'mdi-calendar-star', description: '', event_date: '', event_time: '', location: '', is_published: false }; },
        open(event) {
            if (event) {
                this.editing = true;
                this.editId = event.id;
                this.form = {
                    title: event.title,
                    icon: event.icon || 'mdi-calendar-star',
                    description: event.description || '',
                    event_date: event.event_date || '',
                    event_time: event.event_time || '',
                    location: event.location || '',
                    is_published: event.is_published
                };
            } else {
                this.editing = false;
                this.editId = null;
                this.form = this.emptyForm();
            }
            this.error = null;
            this.iconPickerOpen = false;
            this.iconSearch = '';
            this.show = true;
        },
        close() { this.show = false; this.iconPickerOpen = false; },
        selectIcon(icon) {
            this.form.icon = icon;
            this.iconPickerOpen = false;
            this.iconSearch = '';
        },
        async save() {
            if (!this.form.title.trim()) { this.error = 'Title is required.'; return; }
            if (!this.form.icon) { this.error = 'Please select an icon.'; return; }
            this.saving = true; this.error = null;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const url = this.editing ? '/website-management/events/' + this.editId : '/website-management/events';
            const method = this.editing ? 'PUT' : 'POST';
            try {
                const res = await fetch(url, { method, headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }, body: JSON.stringify(this.form) });
                if (!res.ok) { const d = await res.json().catch(() => null); this.error = d?.message || 'Failed to save.'; this.saving = false; return; }
                this.$emit('saved');
                this.close();
                if (window.toast) window.toast.success('Saved', this.editing ? 'Event updated' : 'Event created');
            } catch(e) { this.error = 'Network error.'; }
            this.saving = false;
        }
    },
    mounted() {
        document.addEventListener('click', (e) => {
            if (this.iconPickerOpen && !e.target.closest('.icon-picker')) {
                this.iconPickerOpen = false;
            }
        });
    }
};
</script>
