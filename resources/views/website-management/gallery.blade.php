@extends('layout')

@section('title', 'Gallery Management')

@section('content')
<div class="col-lg-12 grid-margin stretch-card gallery-page">
    <div class="card shadow-sm border-0" style="border-radius:20px !important; flex:1; display:flex; flex-direction:column;">
        <div class="card-body p-4" style="display:flex; flex-direction:column; flex:1; min-height:0;">

            {{-- PAGE HEADER --}}
            <div class="gallery-header">
                <div class="gallery-header-left">
                    <a href="{{ route('website-management.index') }}" class="gallery-back-btn">
                        <i class="mdi mdi-arrow-left"></i>
                    </a>
                    <div class="gallery-header-icon">
                        <i class="mdi mdi-image-multiple"></i>
                    </div>
                    <div>
                        <h3 class="gallery-page-title">Gallery Management</h3>
                        <p class="gallery-page-subtitle">Manage website albums and photos</p>
                    </div>
                </div>
                <button id="newAlbumBtn" class="btn btn-outline-success btn-sm gallery-new-btn" data-requires="website-management" style="display:none;">
                    <i class="mdi mdi-plus me-1"></i> New Album
                </button>
            </div>

            <hr style="flex-shrink:0; border-color:#f0f4f8; margin-bottom:16px;">

            {{-- TOOLBAR --}}
            <div class="gallery-toolbar">
                <div class="gallery-search-wrap">
                    <i class="mdi mdi-magnify"></i>
                    <input id="gallerySearch" type="text" class="gallery-search-input" placeholder="Search albums...">
                </div>
                <div class="gallery-view-toggle">
                    <button class="gallery-toggle-btn active" id="viewGrid" title="Grid view">
                        <i class="mdi mdi-view-grid"></i>
                    </button>
                    <button class="gallery-toggle-btn" id="viewList" title="List view">
                        <i class="mdi mdi-format-list-bulleted"></i>
                    </button>
                </div>
            </div>

            {{-- VUE ALBUM GRID --}}
            <div class="gallery-scroll-area">
                <div id="gallery-vue-app" style="display:flex; flex-direction:column; flex:1; min-height:0;"></div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wire "New Album" button to Vue
    var newBtn = document.getElementById('newAlbumBtn');
    if (newBtn) {
        newBtn.addEventListener('click', function() {
            window.dispatchEvent(new Event('gallery-open-new-album'));
        });
    }

    // Wire search input to Vue
    var searchInput = document.getElementById('gallerySearch');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            window.dispatchEvent(new CustomEvent('gallery-search', { detail: this.value }));
        });
    }

    // Wire view toggle buttons
    var gridBtn = document.getElementById('viewGrid');
    var listBtn = document.getElementById('viewList');

    if (gridBtn && listBtn) {
        gridBtn.addEventListener('click', function() {
            gridBtn.classList.add('active');
            listBtn.classList.remove('active');
            window.dispatchEvent(new CustomEvent('gallery-view-mode', { detail: 'grid' }));
        });
        listBtn.addEventListener('click', function() {
            listBtn.classList.add('active');
            gridBtn.classList.remove('active');
            window.dispatchEvent(new CustomEvent('gallery-view-mode', { detail: 'list' }));
        });
    }
});
</script>
@endpush
@endsection
