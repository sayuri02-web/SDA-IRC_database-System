@extends('layout')

@section('title', $album->title . ' - Gallery')

@section('content')
<div class="col-lg-12 grid-margin stretch-card gallery-page">
    <div class="card shadow-sm border-0" style="border-radius:20px !important; flex:1; display:flex; flex-direction:column;">
        <div class="card-body p-4" style="display:flex; flex-direction:column; flex:1; min-height:0;">

            {{-- PAGE HEADER --}}
            <div class="gallery-header">
                <div class="gallery-header-left">
                    <a href="{{ route('website-management.gallery') }}" class="gallery-back-btn">
                        <i class="mdi mdi-arrow-left"></i>
                    </a>
                    <div class="gallery-header-icon" style="background: linear-gradient(135deg, {{ $album->gradient_from }}, {{ $album->gradient_to }});">
                        <i class="mdi {{ $album->icon }}"></i>
                    </div>
                    <div>
                        <h3 class="gallery-page-title">{{ $album->title }}</h3>
                        <p class="gallery-page-subtitle">
                            {{ $album->description ?: 'Manage photos in this album' }}
                        </p>
                    </div>
                </div>
                <button class="btn btn-success btn-sm gallery-new-btn" id="uploadPhotosBtn" @if($album->photos_count == 0) style="display:none;" @endif>
                    <i class="mdi mdi-cloud-upload-outline me-1"></i> Upload Photos
                </button>
            </div>

            <hr style="flex-shrink:0; border-color:#f0f4f8; margin-bottom:16px;">

            {{-- TOOLBAR --}}
            <div class="gallery-toolbar">
                <div class="gallery-toolbar-left">
                    <div class="gallery-search-wrap">
                        <i class="mdi mdi-magnify"></i>
                        <input id="albumSearch" type="text" class="gallery-search-input" placeholder="Search photos...">
                    </div>
                    <div class="gallery-toolbar-info">
                        <span class="gallery-album-meta">
                            <i class="mdi mdi-camera-outline"></i> {{ $album->photos_count }} Photos
                        </span>
                    </div>
                </div>
                <div class="gallery-view-toggle">
                    <button class="gallery-toggle-btn active" id="albumViewGrid" title="Grid view">
                        <i class="mdi mdi-view-grid"></i>
                    </button>
                    <button class="gallery-toggle-btn" id="albumViewList" title="List view">
                        <i class="mdi mdi-format-list-bulleted"></i>
                    </button>
                </div>
            </div>

            {{-- VUE PHOTO GRID --}}
            <div class="gallery-scroll-area">
                <div id="album-photos-app" data-album-id="{{ $album->id }}" style="display:flex; flex-direction:column; flex:1; min-height:0;"></div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Upload button
    var uploadBtn = document.getElementById('uploadPhotosBtn');
    if (uploadBtn) {
        uploadBtn.addEventListener('click', function() {
            window.dispatchEvent(new Event('album-upload-photos'));
        });
    }

    // Search
    var searchInput = document.getElementById('albumSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            window.dispatchEvent(new CustomEvent('album-search', { detail: this.value }));
        });
    }

    // View toggle
    var gridBtn = document.getElementById('albumViewGrid');
    var listBtn = document.getElementById('albumViewList');
    if (gridBtn && listBtn) {
        gridBtn.addEventListener('click', function() {
            gridBtn.classList.add('active'); listBtn.classList.remove('active');
            window.dispatchEvent(new CustomEvent('album-view-mode', { detail: 'grid' }));
        });
        listBtn.addEventListener('click', function() {
            listBtn.classList.add('active'); gridBtn.classList.remove('active');
            window.dispatchEvent(new CustomEvent('album-view-mode', { detail: 'list' }));
        });
    }
});
</script>
@endpush
@endsection
