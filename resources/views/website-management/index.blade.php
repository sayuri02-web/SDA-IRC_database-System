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
                            <div class="wm-card-status">
                                <span class="wm-status-label">Current Status</span>
                                @if($cardStatuses['pastor']['status'] === 'published')
                                    <span class="wm-status-badge wm-status-published">● Published</span>
                                @elseif($cardStatuses['pastor']['status'] === 'draft')
                                    <span class="wm-status-badge wm-status-draft">● Draft</span>
                                @else
                                    <span class="wm-status-badge wm-status-not-published">● Not Published</span>
                                @endif
                                <span class="wm-status-label">Last Updated</span>
                                <span class="wm-status-date">{{ $cardStatuses['pastor']['updated_at'] ? $cardStatuses['pastor']['updated_at']->format('F d, Y') : '—' }}</span>
                            </div>
                            <a href="{{ route('website-management.pastor-message') }}"
                                class="wm-manage-btn" data-requires="website-management">
                                    <i class="mdi mdi-pencil-outline me-1"></i>
                                    Manage
                            </a>
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
                            <div class="wm-card-status">
                                <div class="wm-mini-stats">
                                    <div class="wm-mini-stat wm-mini-stat-clickable" data-requires="website-management" onclick="window.dispatchEvent(new Event('open-events-modal'))">
                                        <span class="wm-mini-label">Events</span>
                                        <span class="wm-mini-value">{{ $cardStatuses['events']['events_count'] ?? 0 }}</span>
                                        <span class="wm-mini-action">View →</span>
                                    </div>
                                    <div class="wm-mini-stat wm-mini-stat-clickable" data-requires="website-management" onclick="window.dispatchEvent(new Event('open-announcements-modal'))">
                                        <span class="wm-mini-label">Announcements</span>
                                        <span class="wm-mini-value">{{ $cardStatuses['events']['announcements_count'] ?? 0 }}</span>
                                        <span class="wm-mini-action">View →</span>
                                    </div>
                                </div>
                                <span class="wm-status-label mt-3">Last Updated</span>
                                <span class="wm-status-date">{{ $cardStatuses['events']['updated_at'] ? $cardStatuses['events']['updated_at']->format('F d, Y') : '—' }}</span>
                            </div>
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
                            <div class="wm-card-status">
                                <div class="wm-mini-stats">
                                    <div class="wm-mini-stat wm-mini-stat-clickable wm-mini-stat-full" data-requires="website-management" onclick="window.dispatchEvent(new Event('open-ministries-modal'))">
                                        <span class="wm-mini-label">Total</span>
                                        <span class="wm-mini-value">{{ $cardStatuses['ministries']['count'] ?? 0 }}</span>
                                        <span class="wm-mini-label">Ministries</span>
                                        <span class="wm-mini-action">View →</span>
                                    </div>
                                </div>
                                <span class="wm-status-label mt-3">Last Updated</span>
                                <span class="wm-status-date">{{ $cardStatuses['ministries']['updated_at'] ? $cardStatuses['ministries']['updated_at']->format('F d, Y') : '—' }}</span>
                            </div>
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
                            <div class="wm-card-status">
                                <div class="wm-mini-stats">
                                    <div class="wm-mini-stat">
                                        <span class="wm-mini-label">Albums</span>
                                        <span class="wm-mini-value" style="font-size:22px;">{{ $cardStatuses['gallery']['albums_count'] ?? 0 }}</span>
                                    </div>
                                    <div class="wm-mini-stat">
                                        <span class="wm-mini-label">Photos</span>
                                        <span class="wm-mini-value" style="font-size:22px;">{{ $cardStatuses['gallery']['photos_count'] ?? 0 }}</span>
                                    </div>
                                </div>
                                <span class="wm-status-label mt-3">Last Updated</span>
                                <span class="wm-status-date">{{ $cardStatuses['gallery']['updated_at'] ? $cardStatuses['gallery']['updated_at']->format('F d, Y') : '—' }}</span>
                            </div>
                            <a href="{{ route('website-management.gallery') }}" class="wm-manage-btn" data-requires="website-management">
                                <i class="mdi mdi-pencil-outline me-1"></i> Manage
                            </a>
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

{{-- Vue Events & Announcements Modals --}}
<div id="wm-modals-app"></div>

@endsection
