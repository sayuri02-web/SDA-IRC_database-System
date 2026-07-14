<template>
<div style="display:flex; flex-direction:column; flex:1; min-height:0;">

    <!-- Loading -->
    <div v-if="loading" class="gallery-loading"><div class="wm-spinner"></div><span>Loading photos...</span></div>

    <!-- Empty State -->
    <div v-else-if="filteredPhotos.length === 0 && !search" class="gallery-album-empty">
        <i class="mdi mdi-image-outline"></i>
        <h5>No Photos Yet</h5>
        <p>Upload your first photos to this album.</p>
        <button class="btn btn-success btn-sm" @click="showUpload = true">
            <i class="mdi mdi-cloud-upload-outline me-1"></i>Upload Photos
        </button>
    </div>

    <!-- No Search Results -->
    <div v-else-if="filteredPhotos.length === 0 && search" class="gallery-album-empty">
        <i class="mdi mdi-magnify"></i>
        <p>No photos matching your search.</p>
    </div>

    <!-- Photo Grid -->
    <div v-else :class="viewMode === 'grid' ? 'gallery-photos-grid' : 'gallery-photos-list'">
        <div v-for="photo in filteredPhotos" :key="photo.id"
            :class="viewMode === 'grid' ? 'gallery-photo-card' : 'gallery-photo-list-item'">
            <div class="gallery-photo-thumb" @click="openPreview(photo)">
                <img :src="photo.url" :alt="photo.caption || 'Photo'">
            </div>
            <div v-if="viewMode === 'list'" class="gallery-photo-list-info">
                <span class="gallery-photo-list-name">{{ photo.filename }}</span>
                <span class="gallery-photo-list-date">{{ photo.created_at }}</span>
            </div>
            <div class="gallery-photo-overlay">
                <span class="gallery-photo-date">{{ photo.created_at }}</span>
                <button class="gallery-photo-delete" @click.stop="confirmDelete(photo)" title="Delete">
                    <i class="mdi mdi-delete-outline"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Upload Modal -->
    <Transition name="wm-modal">
    <div v-if="showUpload" class="wm-modal-overlay" @click.self="showUpload = false">
    <div class="wm-modal-content wm-modal-md">
        <div class="wm-modal-header">
            <div class="wm-modal-title"><i class="mdi mdi-cloud-upload-outline"></i><span>Upload Photos</span></div>
            <button class="wm-modal-close" @click="showUpload = false"><i class="mdi mdi-close"></i></button>
        </div>
        <div class="wm-modal-body wm-form-body">
            <div class="gallery-upload-drop" @click="$refs.fileInput.click()" @dragover.prevent @drop.prevent="handleDrop">
                <i class="mdi mdi-cloud-upload-outline"></i>
                <p>Drag & drop images or <span>click to browse</span></p>
                <small>PNG, JPG, WEBP up to 10MB each</small>
                <input type="file" ref="fileInput" style="display:none" accept="image/*" multiple @change="handleFiles">
            </div>
            <div v-if="previews.length" class="gallery-upload-previews">
                <div v-for="(p, i) in previews" :key="i" class="gallery-upload-thumb">
                    <img :src="p.url" alt="">
                    <button @click="removePreview(i)" class="gallery-upload-remove-btn"><i class="mdi mdi-close"></i></button>
                </div>
            </div>
            <div v-if="uploadError" class="wm-form-error">{{ uploadError }}</div>
        </div>
        <div class="wm-modal-footer">
            <button class="btn btn-outline-secondary" @click="showUpload = false">Cancel</button>
            <button class="btn btn-success" @click="upload" :disabled="uploading || !files.length">
                <span v-if="uploading"><i class="mdi mdi-loading mdi-spin"></i> Uploading...</span>
                <span v-else><i class="mdi mdi-cloud-upload-outline me-1"></i>Upload {{ files.length }} Photo{{ files.length !== 1 ? 's' : '' }}</span>
            </button>
        </div>
    </div>
    </div>
    </Transition>

    <!-- Photo Preview -->
    <Transition name="wm-modal">
    <div v-if="previewPhoto" class="wm-modal-overlay" @click.self="previewPhoto = null" style="z-index:10000">
    <div class="gallery-preview-modal">
        <button class="gallery-preview-close" @click="previewPhoto = null"><i class="mdi mdi-close"></i></button>
        <img :src="previewPhoto.url" :alt="previewPhoto.caption || 'Photo'">
        <div class="gallery-preview-info">
            <span>{{ previewPhoto.filename }}</span>
            <span>{{ previewPhoto.created_at }}</span>
        </div>
    </div>
    </div>
    </Transition>

</div>
</template>

<script>
export default {
    name: 'AlbumPhotosPage',
    props: { albumId: { type: [Number, String], required: true } },
    data() {
        return {
            photos: [], loading: true, search: '', viewMode: 'grid',
            showUpload: false, files: [], previews: [], uploading: false, uploadError: null,
            previewPhoto: null,
        };
    },
    computed: {
        filteredPhotos() {
            if (!this.search) return this.photos;
            const q = this.search.toLowerCase();
            return this.photos.filter(p => (p.filename || '').toLowerCase().includes(q) || (p.caption || '').toLowerCase().includes(q));
        }
    },
    methods: {
        async fetchPhotos() {
            this.loading = true;
            try {
                const res = await fetch('/website-management/gallery/' + this.albumId + '/photos');
                this.photos = await res.json();
            } catch (e) { this.photos = []; }
            this.loading = false;
        },
        handleFiles(e) { this.addFiles(Array.from(e.target.files)); },
        handleDrop(e) { this.addFiles(Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'))); },
        addFiles(newFiles) {
            newFiles.forEach(f => {
                this.files.push(f);
                const reader = new FileReader();
                reader.onload = (e) => this.previews.push({ url: e.target.result });
                reader.readAsDataURL(f);
            });
        },
        removePreview(i) { this.files.splice(i, 1); this.previews.splice(i, 1); },
        async upload() {
            if (!this.files.length) return;
            this.uploading = true; this.uploadError = null;
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const formData = new FormData();
            this.files.forEach(f => formData.append('photos[]', f));
            try {
                const res = await fetch('/website-management/gallery/' + this.albumId + '/photos', {
                    method: 'POST', headers: { 'X-CSRF-TOKEN': token }, body: formData
                });
                if (!res.ok) { const d = await res.json().catch(() => null); this.uploadError = d?.message || 'Upload failed.'; this.uploading = false; return; }
                const data = await res.json();
                this.photos.unshift(...data.photos);
                this.showUpload = false; this.files = []; this.previews = [];
                if (window.toast) window.toast.success('Uploaded', data.count + ' photo(s) uploaded successfully.');
                var toolbarBtn = document.getElementById('uploadPhotosBtn');
                if (toolbarBtn) toolbarBtn.style.display = '';
            } catch (e) { this.uploadError = 'Network error.'; }
            this.uploading = false;
        },
        openPreview(photo) { this.previewPhoto = photo; },
        async confirmDelete(photo) {
            document.getElementById('globalDeleteTitle').textContent = 'Delete Photo';
            document.getElementById('globalDeleteMsg').textContent = 'Are you sure you want to delete this photo? This action cannot be undone.';
            const modal = new bootstrap.Modal(document.getElementById('globalDeleteModal'));
            modal.show();
            const confirmBtn = document.getElementById('globalDeleteConfirmBtn');
            const handler = async () => {
                confirmBtn.removeEventListener('click', handler);
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                try {
                    const res = await fetch('/website-management/gallery/photos/' + photo.id, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': token } });
                    if (res.ok) {
                        this.photos = this.photos.filter(p => p.id !== photo.id);
                        if (window.toast) window.toast.success('Deleted', 'Photo deleted successfully.');
                    }
                } catch (e) { if (window.toast) window.toast.error('Error', 'Failed to delete photo.'); }
                modal.hide();
            };
            confirmBtn.addEventListener('click', handler);
        },
        setSearch(val) { this.search = val; },
        setViewMode(mode) { this.viewMode = mode; }
    },
    mounted() {
        this.fetchPhotos();
        window.addEventListener('album-upload-photos', () => { this.showUpload = true; });
        window.addEventListener('album-search', (e) => this.setSearch(e.detail || ''));
        window.addEventListener('album-view-mode', (e) => this.setViewMode(e.detail || 'grid'));
    }
};
</script>

<style>
.gallery-photos-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; }
.gallery-photo-card { position: relative; border-radius: 12px; overflow: hidden; aspect-ratio: 1; background: #f0f4f8; cursor: pointer; }
.gallery-photo-thumb { width: 100%; height: 100%; }
.gallery-photo-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
.gallery-photo-card:hover .gallery-photo-thumb img { transform: scale(1.05); }
.gallery-photo-overlay { position: absolute; bottom: 0; left: 0; right: 0; padding: 8px 10px; background: linear-gradient(transparent, rgba(0,0,0,0.6)); display: flex; align-items: center; justify-content: space-between; opacity: 0; transition: opacity 0.25s; }
.gallery-photo-card:hover .gallery-photo-overlay { opacity: 1; }
.gallery-photo-date { font-size: 11px; color: #fff; font-weight: 500; }
.gallery-photo-delete { width: 26px; height: 26px; border-radius: 50%; border: none; background: rgba(229,57,53,0.85); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 14px; cursor: pointer; transition: 0.2s; }
.gallery-photo-delete:hover { background: #e53935; transform: scale(1.1); }

/* List view */
.gallery-photos-list { display: flex; flex-direction: column; gap: 8px; }
.gallery-photo-list-item { display: flex; align-items: center; gap: 12px; padding: 10px 14px; border: 1px solid #edf0f5; border-radius: 10px; background: #fff; }
.gallery-photo-list-item .gallery-photo-thumb { width: 48px; height: 48px; border-radius: 8px; overflow: hidden; flex-shrink: 0; cursor: pointer; }
.gallery-photo-list-item .gallery-photo-thumb img { width: 100%; height: 100%; object-fit: cover; }
.gallery-photo-list-info { flex: 1; }
.gallery-photo-list-name { display: block; font-size: 13px; font-weight: 600; color: #1a1f36; }
.gallery-photo-list-date { display: block; font-size: 12px; color: #8898aa; }
.gallery-photo-list-item .gallery-photo-overlay { position: static; background: none; opacity: 1; padding: 0; flex-shrink: 0; }
.gallery-photo-list-item .gallery-photo-date { display: none; }

/* Upload drop area */
.gallery-upload-drop { border: 2px dashed #d8dfe8; border-radius: 14px; padding: 40px 20px; text-align: center; cursor: pointer; transition: 0.25s; background: #fafbfd; }
.gallery-upload-drop:hover { border-color: #8e3dff; background: rgba(142,61,255,0.02); }
.gallery-upload-drop i { font-size: 36px; color: #b0b8c4; margin-bottom: 8px; display: block; }
.gallery-upload-drop p { font-size: 13px; color: #8898aa; margin: 0 0 4px; }
.gallery-upload-drop p span { color: #8e3dff; font-weight: 600; }
.gallery-upload-drop small { font-size: 11px; color: #b0b8c4; }

/* Upload previews */
.gallery-upload-previews { display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 8px; margin-top: 16px; }
.gallery-upload-thumb { position: relative; aspect-ratio: 1; border-radius: 8px; overflow: hidden; }
.gallery-upload-thumb img { width: 100%; height: 100%; object-fit: cover; }
.gallery-upload-remove-btn { position: absolute; top: 4px; right: 4px; width: 20px; height: 20px; border-radius: 50%; border: none; background: rgba(0,0,0,0.6); color: #fff; font-size: 12px; display: flex; align-items: center; justify-content: center; cursor: pointer; }

/* Preview modal */
.gallery-preview-modal { position: relative; max-width: 90vw; max-height: 90vh; display: flex; flex-direction: column; align-items: center; }
.gallery-preview-modal img { max-width: 100%; max-height: 80vh; border-radius: 12px; object-fit: contain; box-shadow: 0 16px 48px rgba(0,0,0,0.3); }
.gallery-preview-close { position: absolute; top: -40px; right: 0; width: 36px; height: 36px; border-radius: 50%; border: none; background: rgba(255,255,255,0.15); color: #fff; font-size: 20px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; }
.gallery-preview-close:hover { background: rgba(255,255,255,0.3); }
.gallery-preview-info { display: flex; gap: 16px; margin-top: 12px; font-size: 13px; color: rgba(255,255,255,0.7); }

@media (max-width: 768px) {
    .gallery-photos-grid { grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 8px; }
}
@media (max-width: 480px) {
    .gallery-photos-grid { grid-template-columns: repeat(2, 1fr); gap: 6px; }
}
</style>
