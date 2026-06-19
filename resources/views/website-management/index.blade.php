@extends('layout')

@section('title', 'Website Management')

@section('content')
<div class="col-lg-12 grid-margin stretch-card wm-page-wrapper">
    <div class="card shadow-sm border-0 wm-page-card">
        <div class="card-body p-4 wm-page-body">

            {{-- PAGE HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="church-page-icon" style="background: linear-gradient(135deg, #7f53ac, #647dee);">
                        <i class="mdi mdi-web"></i>
                    </div>
                    <div>
                        <h3 class="church-page-title mb-0">Website Management</h3>
                        <p class="church-page-subtitle mb-0">Manage content displayed on the church website</p>
                    </div>
                </div>
                <a href="{{ url('/website') }}" target="_blank" class="btn btn-outline-primary btn-sm">
                    <i class="mdi mdi-open-in-new me-1"></i> View Website
                </a>
            </div>

            {{-- MANAGEMENT CARDS --}}
            <div class="wm-scroll-area">
                <div class="row g-3">

                    {{-- Pastor's Message --}}
                    <div class="col-md-6 col-xl-3">
                        <div class="wm-card">
                            <div class="wm-card-icon" style="background: #eef4ff; color: #2449d8;">
                                <i class="mdi mdi-account-voice"></i>
                            </div>
                            <h5 class="wm-card-title">Pastor's Message</h5>
                            <p class="wm-card-desc">Manage the pastor's featured message for the website.</p>
                            <button class="wm-manage-btn" data-bs-toggle="modal" data-bs-target="#pastorMessageModal">
                                <i class="mdi mdi-pencil-outline me-1"></i> Manage
                            </button>
                        </div>
                    </div>

                    {{-- Events & Announcements --}}
                    <div class="col-md-6 col-xl-3">
                        <div class="wm-card">
                            <div class="wm-card-icon" style="background: #e9fff3; color: #28a745;">
                                <i class="mdi mdi-calendar-month"></i>
                            </div>
                            <h5 class="wm-card-title">Events & Announcements</h5>
                            <p class="wm-card-desc">Manage church events and announcements.</p>
                            <button class="wm-manage-btn" data-bs-toggle="modal" data-bs-target="#eventsModal">
                                <i class="mdi mdi-pencil-outline me-1"></i> Manage
                            </button>
                        </div>
                    </div>

                    {{-- Ministries --}}
                    <div class="col-md-6 col-xl-3">
                        <div class="wm-card">
                            <div class="wm-card-icon" style="background: #fff4e8; color: #ff8a00;">
                                <i class="mdi mdi-account-group"></i>
                            </div>
                            <h5 class="wm-card-title">Ministries</h5>
                            <p class="wm-card-desc">Manage church ministries and descriptions.</p>
                            <button class="wm-manage-btn" data-bs-toggle="modal" data-bs-target="#ministriesModal">
                                <i class="mdi mdi-pencil-outline me-1"></i> Manage
                            </button>
                        </div>
                    </div>

                    {{-- Gallery --}}
                    <div class="col-md-6 col-xl-3">
                        <div class="wm-card">
                            <div class="wm-card-icon" style="background: #f7ecff; color: #8e3dff;">
                                <i class="mdi mdi-image-multiple"></i>
                            </div>
                            <h5 class="wm-card-title">Gallery</h5>
                            <p class="wm-card-desc">Manage website photos and gallery albums.</p>
                            <button class="wm-manage-btn" data-bs-toggle="modal" data-bs-target="#galleryModal">
                                <i class="mdi mdi-pencil-outline me-1"></i> Manage
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- ======================== MODALS ======================== --}}

{{-- Pastor Message Modal --}}
<div class="modal fade" id="pastorMessageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content leaders-modal">
            <div class="leaders-modal-bar" style="background: linear-gradient(to right, #2449d8, #5c7cfa);"></div>
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <h5 class="fw-bold mb-0"><i class="mdi mdi-account-voice me-2" style="color:#2449d8;"></i>Pastor's Message</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <p class="text-muted">Content management coming soon...</p>
            </div>
        </div>
    </div>
</div>

{{-- Events Modal --}}
<div class="modal fade" id="eventsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content leaders-modal">
            <div class="leaders-modal-bar" style="background: linear-gradient(to right, #28a745, #5cd65c);"></div>
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <h5 class="fw-bold mb-0"><i class="mdi mdi-calendar-month me-2" style="color:#28a745;"></i>Events & Announcements</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <p class="text-muted">Content management coming soon...</p>
            </div>
        </div>
    </div>
</div>

{{-- Ministries Modal --}}
<div class="modal fade" id="ministriesModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content leaders-modal">
            <div class="leaders-modal-bar" style="background: linear-gradient(to right, #ff8a00, #ffb347);"></div>
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <h5 class="fw-bold mb-0"><i class="mdi mdi-account-group me-2" style="color:#ff8a00;"></i>Ministries</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <p class="text-muted">Content management coming soon...</p>
            </div>
        </div>
    </div>
</div>

{{-- Gallery Modal --}}
<div class="modal fade" id="galleryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content leaders-modal">
            <div class="leaders-modal-bar" style="background: linear-gradient(to right, #8e3dff, #b388ff);"></div>
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <h5 class="fw-bold mb-0"><i class="mdi mdi-image-multiple me-2" style="color:#8e3dff;"></i>Gallery</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <p class="text-muted">Content management coming soon...</p>
            </div>
        </div>
    </div>
</div>
@endsection
