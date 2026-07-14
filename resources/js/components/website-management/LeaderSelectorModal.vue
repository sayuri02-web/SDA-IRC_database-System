<template>
<Transition name="wm-modal">
<div class="wm-modal-overlay" @click.self="$emit('close')" style="z-index:10000;">
<div class="wm-modal-content wm-modal-lg">
    <div class="wm-modal-header">
        <div class="wm-modal-title"><i class="mdi mdi-account-group-outline"></i><span>Select Leaders</span></div>
        <button class="wm-modal-close" @click="$emit('close')"><i class="mdi mdi-close"></i></button>
    </div>
    <div class="wm-modal-body">
        <!-- Search -->
        <div class="wm-modal-search-wrap mb-3">
            <i class="mdi mdi-magnify"></i>
            <input type="text" v-model="search" @input="fetchLeaders" placeholder="Search officers...">
        </div>

        <!-- Loading -->
        <div v-if="loading" class="text-center py-4">
            <div class="wm-spinner"></div>
        </div>

        <!-- Leaders List -->
        <div v-else class="leader-selector-list">
            <div v-if="officers.length === 0" class="text-center py-4 text-muted" style="font-size:13px;">
                No officers found.
            </div>
            <div v-for="officer in officers" :key="officer.id" class="leader-selector-item" @click="toggleSelection(officer.id)">
                <input type="checkbox" :checked="isSelected(officer.id)" class="form-check-input" style="flex-shrink:0;">
                <div class="leader-selector-avatar">
                    <img v-if="officer.photo" :src="'/uploads/' + officer.photo" alt="">
                    <i v-else class="mdi mdi-account"></i>
                </div>
                <div class="leader-selector-info">
                    <strong>{{ officer.full_name }}</strong>
                    <small>{{ [officer.position, officer.organization, officer.church].filter(Boolean).join(' • ') }}</small>
                </div>
            </div>
        </div>
    </div>
    <div class="wm-modal-footer">
        <span class="text-muted" style="font-size:12px;">{{ selected.length }} selected</span>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm" @click="$emit('close')">Cancel</button>
            <button class="btn btn-success btn-sm" @click="save">
                <i class="mdi mdi-check me-1"></i> Save Leaders
            </button>
        </div>
    </div>
</div>
</div>
</Transition>
</template>

<script>
export default {
    name: 'LeaderSelectorModal',
    props: {
        selectedIds: { type: Array, default: () => [] }
    },
    emits: ['close', 'save'],
    data() {
        return {
            officers: [],
            selected: [...this.selectedIds],
            search: '',
            loading: false,
            debounce: null,
        };
    },
    methods: {
        isSelected(id) {
            return this.selected.includes(id);
        },
        toggleSelection(id) {
            if (this.isSelected(id)) {
                this.selected = this.selected.filter(s => s !== id);
            } else {
                this.selected.push(id);
            }
        },
        fetchLeaders() {
            clearTimeout(this.debounce);
            this.debounce = setTimeout(async () => {
                this.loading = true;
                try {
                    const res = await fetch('/website-management/about/leaders?search=' + encodeURIComponent(this.search));
                    this.officers = await res.json();
                } catch (e) { this.officers = []; }
                this.loading = false;
            }, 200);
        },
        save() {
            this.$emit('save', this.selected);
        }
    },
    mounted() {
        this.fetchLeaders();
    }
};
</script>

<style scoped>
.leader-selector-list { max-height: 400px; overflow-y: auto; display: flex; flex-direction: column; gap: 4px; }
.leader-selector-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 10px; cursor: pointer; transition: background 0.15s; }
.leader-selector-item:hover { background: #f0faf3; }
.leader-selector-avatar { width: 34px; height: 34px; border-radius: 50%; background: #e9fff3; border: 2px solid #28a745; display: flex; align-items: center; justify-content: center; overflow: hidden; flex-shrink: 0; }
.leader-selector-avatar img { width: 100%; height: 100%; object-fit: cover; }
.leader-selector-avatar i { font-size: 16px; color: #28a745; }
.leader-selector-info { flex: 1; }
.leader-selector-info strong { display: block; font-size: 13px; color: #1a1f36; }
.leader-selector-info small { font-size: 11px; color: #8898aa; }
.wm-modal-footer { display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-top: 1px solid #edf0f5; }
.wm-modal-lg { max-width: 600px; }
</style>
