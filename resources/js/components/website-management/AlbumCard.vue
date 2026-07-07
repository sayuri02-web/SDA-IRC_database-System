<template>
    <div class="gallery-album-card">
        <div class="gallery-album-cover" :style="{ background: gradient }">
            <i :class="'mdi ' + album.icon + ' gallery-album-cover-icon'"></i>
            <span class="gallery-album-count">
                <i class="mdi mdi-camera-outline"></i> {{ album.photos_count }}
            </span>
            <span :class="['gallery-album-status', album.is_published ? 'gallery-status-published' : 'gallery-status-draft']">
                {{ album.is_published ? 'Published' : 'Draft' }}
            </span>
        </div>
        <div class="gallery-album-body">
            <h5 class="gallery-album-title">{{ album.title }}</h5>
            <span class="gallery-album-date">
                <i class="mdi mdi-clock-outline"></i> {{ album.updated_at }}
            </span>
        </div>
        <div class="gallery-album-actions">
            <a :href="'/website-management/gallery/' + album.id" class="gallery-action-btn gallery-action-open" title="Open Album">
                <i class="mdi mdi-folder-open-outline"></i> Open
            </a>
            <button class="gallery-action-btn gallery-action-edit" title="Edit" @click="$emit('edit', album)">
                <i class="mdi mdi-pencil-outline"></i>
            </button>
            <button class="gallery-action-btn gallery-action-delete" title="Delete" @click="$emit('delete', album)">
                <i class="mdi mdi-delete-outline"></i>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AlbumCard',
    props: {
        album: { type: Object, required: true }
    },
    emits: ['edit', 'delete'],
    computed: {
        gradient() {
            return `linear-gradient(135deg, ${this.album.gradient_from || '#667eea'}, ${this.album.gradient_to || '#764ba2'})`;
        }
    }
};
</script>

<style>
.gallery-album-cover-icon {
    font-size: 52px;
    color: rgba(255, 255, 255, 0.85);
    transition: transform 0.35s ease;
}
.gallery-album-card:hover .gallery-album-cover-icon {
    transform: scale(1.12);
}
.gallery-album-status {
    position: absolute;
    top: 10px; left: 10px;
    padding: 3px 10px; border-radius: 20px;
    font-size: 10px; font-weight: 600;
    backdrop-filter: blur(4px);
}
.gallery-status-published { background: rgba(40,167,69,0.85); color: #fff; }
.gallery-status-draft { background: rgba(0,0,0,0.5); color: #fff; }
</style>
