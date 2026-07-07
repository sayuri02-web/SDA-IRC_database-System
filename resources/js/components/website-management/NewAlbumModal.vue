<template>
<Transition name="wm-modal">
<div v-if="show" class="wm-modal-overlay" @click.self="close">
<div class="wm-modal-content wm-modal-md" style="max-height:90vh;">

    <div class="wm-modal-header">
        <div class="wm-modal-title">
            <i class="mdi mdi-folder-plus-outline"></i>
            <span>New Album</span>
        </div>
        <button class="wm-modal-close" @click="close"><i class="mdi mdi-close"></i></button>
    </div>

    <div class="wm-modal-body wm-form-body" style="overflow-y:auto;">

        <!-- Preview -->
        <div class="gallery-form-preview" :style="{ background: previewGradient }">
            <i :class="'mdi ' + form.icon" style="font-size:40px; color:rgba(255,255,255,0.85);"></i>
        </div>

        <div class="wm-form-field">
            <label>Album Name <span class="text-danger">*</span></label>
            <input type="text" v-model="form.title" placeholder="e.g. Sabbath Worship" ref="nameInput">
        </div>

        <div class="wm-form-field">
            <label>Description</label>
            <textarea v-model="form.description" rows="3" placeholder="Brief album description..."></textarea>
        </div>

        <!-- Icon Picker -->
        <div class="wm-form-field">
            <label>Album Icon</label>
            <div class="gallery-icon-grid">
                <button v-for="ic in icons" :key="ic" type="button"
                    :class="['gallery-icon-option', { 'gallery-icon-active': form.icon === ic }]"
                    @click="form.icon = ic" :title="ic">
                    <i :class="'mdi ' + ic"></i>
                </button>
            </div>
        </div>

        <!-- Gradient Picker -->
        <div class="wm-form-field">
            <label>Gradient Theme</label>
            <div class="gallery-gradient-grid">
                <button v-for="g in gradients" :key="g.name" type="button"
                    :class="['gallery-gradient-option', { 'gallery-gradient-active': form.gradient_from === g.from }]"
                    :style="{ background: 'linear-gradient(135deg, ' + g.from + ', ' + g.to + ')' }"
                    @click="selectGradient(g)"
                    :title="g.name">
                </button>
            </div>
        </div>

        <div class="wm-form-field">
            <label>Status</label>
            <select v-model="form.is_published">
                <option value="0">Draft</option>
                <option value="1">Published</option>
            </select>
        </div>

        <div v-if="error" class="wm-form-error">{{ error }}</div>

    </div>

    <div class="wm-modal-footer">
        <button class="btn btn-outline-secondary" @click="close">Cancel</button>
        <button class="btn btn-success" @click="create" :disabled="saving">
            <span v-if="saving"><i class="mdi mdi-loading mdi-spin"></i></span>
            <span v-else><i class="mdi mdi-plus me-1"></i>Create Album</span>
        </button>
    </div>

</div>
</div>
</Transition>
</template>

<script>
export default {
    name: 'NewAlbumModal',
    emits: ['created'],
    data() {
        return {
            show: false,
            saving: false,
            error: null,
            form: { title: '', description: '', icon: 'mdi-image-multiple', gradient_from: '#667eea', gradient_to: '#764ba2', is_published: '0' },
            icons: [
                'mdi-church', 'mdi-account-group', 'mdi-water', 'mdi-hand-heart',
                'mdi-star-outline', 'mdi-music-note', 'mdi-image-multiple', 'mdi-calendar',
                'mdi-cross', 'mdi-book-open-variant', 'mdi-school', 'mdi-human-male-female-child',
                'mdi-microphone', 'mdi-earth', 'mdi-campfire', 'mdi-home-heart',
                'mdi-compass-outline', 'mdi-hands-pray', 'mdi-heart-outline', 'mdi-shield-account',
            ],
            gradients: [
                { name: 'Purple', from: '#667eea', to: '#764ba2' },
                { name: 'Green', from: '#11998e', to: '#38ef7d' },
                { name: 'Blue', from: '#4facfe', to: '#00f2fe' },
                { name: 'Orange', from: '#fa709a', to: '#fee140' },
                { name: 'Pink', from: '#f093fb', to: '#f5576c' },
                { name: 'Indigo', from: '#7f53ac', to: '#647dee' },
                { name: 'Cyan', from: '#36d1dc', to: '#5b86e5' },
                { name: 'Emerald', from: '#28a745', to: '#5cd65c' },
            ]
        };
    },
    computed: {
        previewGradient() {
            return `linear-gradient(135deg, ${this.form.gradient_from}, ${this.form.gradient_to})`;
        }
    },
    methods: {
        open() {
            this.form = { title: '', description: '', icon: 'mdi-image-multiple', gradient_from: '#667eea', gradient_to: '#764ba2', is_published: '0' };
            this.error = null;
            this.show = true;
            document.body.style.overflow = 'hidden';
            this.$nextTick(() => { if (this.$refs.nameInput) this.$refs.nameInput.focus(); });
        },
        close() { this.show = false; document.body.style.overflow = ''; },
        selectGradient(g) {
            this.form.gradient_from = g.from;
            this.form.gradient_to = g.to;
        },
        async create() {
            if (!this.form.title.trim()) { this.error = 'Album name is required.'; return; }
            this.saving = true; this.error = null;

            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            try {
                const res = await fetch('/website-management/gallery/albums', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
                    body: JSON.stringify({
                        title: this.form.title.trim(),
                        description: this.form.description.trim(),
                        icon: this.form.icon,
                        gradient_from: this.form.gradient_from,
                        gradient_to: this.form.gradient_to,
                        is_published: this.form.is_published,
                    })
                });
                if (!res.ok) {
                    const d = await res.json().catch(() => null);
                    if (d && d.errors) {
                        this.error = Object.values(d.errors).flat().join(' ');
                    } else {
                        this.error = d?.message || 'Server error (' + res.status + ')';
                    }
                    this.saving = false; return;
                }
                const data = await res.json();
                this.$emit('created', data.album);
                this.close();
                if (window.toast) window.toast.success('Album Created', '"' + data.album.title + '" has been created.');
            } catch (e) { this.error = 'Network error: ' + e.message; }
            this.saving = false;
        }
    }
};
</script>

<style>
/* Preview card */
.gallery-form-preview {
    height: 100px; border-radius: 12px; margin-bottom: 18px;
    display: flex; align-items: center; justify-content: center;
    transition: background 0.3s;
}

/* Icon grid */
.gallery-icon-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(42px, 1fr)); gap: 6px;
}
.gallery-icon-option {
    width: 42px; height: 42px; border-radius: 8px;
    border: 1.5px solid #edf0f5; background: #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: 20px; color: #525f7f; cursor: pointer; transition: 0.2s;
}
.gallery-icon-option:hover { border-color: #8e3dff; color: #8e3dff; background: #f7ecff; }
.gallery-icon-active { border-color: #8e3dff !important; color: #8e3dff !important; background: #f7ecff !important; box-shadow: 0 0 0 2px rgba(142,61,255,0.15); }

/* Gradient grid */
.gallery-gradient-grid {
    display: flex; flex-wrap: wrap; gap: 8px;
}
.gallery-gradient-option {
    width: 40px; height: 40px; border-radius: 10px;
    border: 2px solid transparent; cursor: pointer; transition: 0.2s;
}
.gallery-gradient-option:hover { transform: scale(1.1); box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
.gallery-gradient-active { border-color: #1a1f36 !important; box-shadow: 0 0 0 3px rgba(0,0,0,0.1); transform: scale(1.1); }
</style>
