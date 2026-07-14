<template>
    <div style="display:flex; flex-direction:column; flex:1; min-height:0;">
        <!-- Loading -->
        <div v-if="loading" class="gallery-loading">
            <div class="wm-spinner"></div>
            <span>Loading albums...</span>
        </div>

        <!-- Empty State -->
        <div v-else-if="filteredAlbums.length === 0 && !search" class="gallery-empty-state">
            <i class="mdi mdi-image-filter-hdr"></i>
            <h5>No Albums Yet</h5>
            <p>Create your first gallery album to start uploading photos.</p>
            <button class="btn btn-outline-success btn-sm" @click="openNewAlbumModal">
                <i class="mdi mdi-plus me-1"></i> New Album
            </button>
        </div>

        <!-- No Search Results -->
        <div v-else-if="filteredAlbums.length === 0 && search" class="gallery-empty-state">
            <i class="mdi mdi-magnify"></i>
            <p>No albums matching "{{ search }}"</p>
        </div>

        <!-- Album Grid -->
        <div v-else :class="viewMode === 'grid' ? 'row g-3' : ''">
            <template v-if="viewMode === 'grid'">
                <div v-for="album in filteredAlbums" :key="album.id" class="col-xl-4 col-md-6">
                    <AlbumCard :album="album" @edit="openEditModal" @delete="confirmDelete" />
                </div>
            </template>
            <template v-else>
                <div class="gallery-list-view">
                    <div v-for="album in filteredAlbums" :key="album.id" class="gallery-list-item">
                        <div class="gallery-list-cover" :style="{ background: 'linear-gradient(135deg, ' + album.gradient_from + ', ' + album.gradient_to + ')' }">
                            <i :class="'mdi ' + album.icon" style="color:rgba(255,255,255,0.85);"></i>
                        </div>
                        <div class="gallery-list-info">
                            <h6>{{ album.title }}</h6>
                            <span>{{ album.photos_count }} photos · {{ album.updated_at }}</span>
                        </div>
                        <span :class="['wm-badge', album.is_published ? 'wm-badge-green' : 'wm-badge-gray']">
                            {{ album.is_published ? 'Published' : 'Draft' }}
                        </span>
                        <div class="gallery-list-actions">
                            <a :href="'/website-management/gallery/' + album.id" class="gallery-action-btn gallery-action-open"><i class="mdi mdi-folder-open-outline"></i></a>
                            <button class="gallery-action-btn gallery-action-edit" @click="openEditModal(album)"><i class="mdi mdi-pencil-outline"></i></button>
                            <button class="gallery-action-btn gallery-action-delete" @click="confirmDelete(album)"><i class="mdi mdi-delete-outline"></i></button>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- New Album Modal -->
        <NewAlbumModal ref="newAlbumModal" @created="onAlbumCreated" />

        <!-- Edit Album Modal -->
        <EditAlbumModal ref="editAlbumModal" @updated="onAlbumUpdated" />
    </div>
</template>

<script>
import AlbumCard from './AlbumCard.vue';
import NewAlbumModal from './NewAlbumModal.vue';
import EditAlbumModal from './EditAlbumModal.vue';

export default {
    name: 'GalleryPage',
    components: { AlbumCard, NewAlbumModal, EditAlbumModal },
    data() {
        return {
            search: '',
            viewMode: 'grid',
            albums: [],
            loading: true,
        };
    },
    computed: {
        filteredAlbums() {
            if (!this.search) return this.albums;
            const q = this.search.toLowerCase();
            return this.albums.filter(a => a.title.toLowerCase().includes(q));
        }
    },
    methods: {
        openNewAlbumModal() {
            this.$refs.newAlbumModal.open();
        },
        openEditModal(album) {
            this.$refs.editAlbumModal.open(album);
        },
        async confirmDelete(album) {
            // Use the global delete confirmation modal
            document.getElementById('globalDeleteTitle').textContent = 'Delete Album';
            document.getElementById('globalDeleteMsg').textContent = 'Delete "' + album.title + '"? All photos in this album will be permanently removed.';

            const modal = new bootstrap.Modal(document.getElementById('globalDeleteModal'));
            modal.show();

            // Set up the confirm handler
            const confirmBtn = document.getElementById('globalDeleteConfirmBtn');
            const handler = async () => {
                confirmBtn.removeEventListener('click', handler);
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                try {
                    const res = await fetch('/website-management/gallery/albums/' + album.id, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': token }
                    });
                    if (res.ok) {
                        this.albums = this.albums.filter(a => a.id !== album.id);
                        if (window.toast) window.toast.success('Deleted', '"' + album.title + '" has been deleted.');
                    }
                } catch (e) {
                    if (window.toast) window.toast.error('Error', 'Failed to delete album.');
                }
                modal.hide();
            };
            confirmBtn.addEventListener('click', handler);
        },
        async fetchAlbums() {
            this.loading = true;
            try {
                const res = await fetch('/website-management/gallery/albums');
                this.albums = await res.json();
            } catch (e) {
                this.albums = [];
            }
            this.loading = false;
            this.toggleToolbarBtn();
        },
        onAlbumCreated(album) {
            this.albums.unshift(album);
            this.toggleToolbarBtn();
        },
        onAlbumUpdated(updated) {
            const index = this.albums.findIndex(a => a.id === updated.id);
            if (index > -1) this.albums.splice(index, 1, updated);
        },
        setSearch(val) {
            this.search = val;
        },
        setViewMode(mode) {
            this.viewMode = mode;
        },
        toggleToolbarBtn() {
            var btn = document.getElementById('newAlbumBtn');
            if (btn) btn.style.display = this.albums.length > 0 ? '' : 'none';
        }
    },
    mounted() {
        this.fetchAlbums();
        window.addEventListener('gallery-open-new-album', () => this.openNewAlbumModal());
        window.addEventListener('gallery-search', (e) => this.setSearch(e.detail || ''));
        window.addEventListener('gallery-view-mode', (e) => this.setViewMode(e.detail || 'grid'));
    }
};
</script>

<style>
.gallery-loading {
    display: flex; align-items: center; justify-content: center; gap: 10px;
    padding: 60px; color: #8898aa; font-size: 14px;
    flex: 1;
}
.gallery-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 40px 20px;
    flex: 1;
    min-height: 300px;
}
.gallery-empty-state > i { font-size: 56px; color: #d0d5dc; margin-bottom: 16px; display: block; }
.gallery-empty-state > h5 { font-size: 16px; font-weight: 700; color: #1a1f36; margin: 0 0 8px; }
.gallery-empty-state > p { font-size: 14px; color: #8898aa; margin: 0 0 20px; }

/* List view */
.gallery-list-view { display: flex; flex-direction: column; gap: 8px; }
.gallery-list-item {
    display: flex; align-items: center; gap: 14px;
    padding: 12px 16px; background: #fff; border: 1px solid #edf0f5;
    border-radius: 12px; transition: box-shadow 0.2s;
}
.gallery-list-item:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
.gallery-list-cover {
    width: 48px; height: 48px; border-radius: 10px; overflow: hidden;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.gallery-list-cover i { font-size: 22px; }
.gallery-list-info { flex: 1; min-width: 0; }
.gallery-list-info h6 { font-size: 14px; font-weight: 600; color: #1a1f36; margin: 0 0 2px; }
.gallery-list-info span { font-size: 12px; color: #8898aa; }
.gallery-list-actions { display: flex; gap: 4px; }
</style>
