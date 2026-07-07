<template>
<Transition name="wm-modal">
<div v-if="show" class="wm-modal-overlay" @click.self="close">
<div class="wm-modal-content wm-modal-md" style="max-height:90vh;">

    <div class="wm-modal-header">
        <div class="wm-modal-title">
            <i class="mdi mdi-pencil-outline"></i>
            <span>Edit Album</span>
        </div>
        <button class="wm-modal-close" @click="close"><i class="mdi mdi-close"></i></button>
    </div>

    <div class="wm-modal-body wm-form-body" style="overflow-y:auto;">

        <div class="gallery-form-preview" :style="{ background: previewGradient }">
            <i :class="'mdi ' + form.icon" style="font-size:40px; color:rgba(255,255,255,0.85);"></i>
        </div>

        <div class="wm-form-field">
            <label>Album Name <span class="text-danger">*</span></label>
            <input type="text" v-model="form.title" placeholder="Album name" ref="nameInput">
        </div>

        <div class="wm-form-field">
            <label>Description</label>
            <textarea v-model="form.description" rows="3" placeholder="Album description..."></textarea>
        </div>

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

        <div class="wm-form-field">
            <label>Gradient Theme</label>
            <div class="gallery-gradient-grid">
                <button v-for="g in gradients" :key="g.name" type="button"
                    :class="['gallery-gradient-option', { 'gallery-gradient-active': form.gradient_from === g.from }]"
                    :style="{ background: 'linear-gradient(135deg, ' + g.from + ', ' + g.to + ')' }"
                    @click="selectGradient(g)" :title="g.name">
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
        <button class="btn btn-success" @click="save" :disabled="saving">
            <span v-if="saving"><i class="mdi mdi-loading mdi-spin"></i></span>
            <span v-else><i class="mdi mdi-content-save-outline me-1"></i>Save Changes</span>
        </button>
    </div>

</div>
</div>
</Transition>
</template>

<script>
export default {
    name: 'EditAlbumModal',
    emits: ['updated'],
    data() {
        return {
            show: false,
            saving: false,
            error: null,
            albumId: null,
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
        open(album) {
            this.albumId = album.id;
            this.form = {
                title: album.title,
                description: album.description || '',
                icon: album.icon || 'mdi-image-multiple',
                gradient_from: album.gradient_from || '#667eea',
                gradient_to: album.gradient_to || '#764ba2',
                is_published: album.is_published ? '1' : '0',
            };
            this.error = null;
            this.show = true;
            document.body.style.overflow = 'hidden';
        },
        close() { this.show = false; document.body.style.overflow = ''; },
        selectGradient(g) { this.form.gradient_from = g.from; this.form.gradient_to = g.to; },
        async save() {
            if (!this.form.title.trim()) { this.error = 'Album name is required.'; return; }
            this.saving = true; this.error = null;

            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            try {
                const res = await fetch('/website-management/gallery/albums/' + this.albumId, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': token },
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
                    this.error = d?.message || 'Failed to update.';
                    this.saving = false; return;
                }
                const data = await res.json();
                this.$emit('updated', data.album);
                this.close();
                if (window.toast) window.toast.success('Album Updated', '"' + data.album.title + '" has been updated.');
            } catch (e) { this.error = 'Network error.'; }
            this.saving = false;
        }
    }
};
</script>
