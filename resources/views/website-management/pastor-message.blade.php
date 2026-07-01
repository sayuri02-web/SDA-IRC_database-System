@extends('layout')

@section('title', 'Pastor\'s Message')

@section('content')

<div class="col-lg-12 grid-margin stretch-card pm-page-wrapper">
    <div class="card shadow-sm border-0 pm-page-card">
        <div class="card-body p-4 pm-page-body">

            {{-- PAGE HEADER --}}
            <div class="pm-header">
                <div class="pm-header-left">
                    <a href="{{ route('website-management.index') }}" class="pm-back-btn">
                        <i class="mdi mdi-arrow-left"></i>
                    </a>
                    <div class="pm-header-icon">
                        <i class="mdi mdi-account-voice"></i>
                    </div>
                    <div>
                        <h3 class="pm-page-title">Pastor's Message</h3>
                        <p class="pm-page-subtitle">Manage the message displayed on the website homepage</p>
                    </div>
                </div>
            </div>

            <hr class="mb-3" style="flex-shrink:0; border-color: #f0f4f8;">

            {{-- SCROLLABLE CONTENT --}}
            <div class="pm-scroll-area">
            <form method="POST" action="{{ route('website-management.pastor-message.save') }}">
                @csrf
                <input type="hidden" id="pastorMemberId" name="member_id" value="{{ $pastorMessage->member_id ?? '' }}">

                {{-- TWO-COLUMN LAYOUT --}}
                <div class="pm-content">

                    {{-- LEFT: EDITOR COLUMN --}}
                    <div class="pm-editor-col">

                        <div class="pm-top-grid">
                            {{-- Pastor Information --}}
                            <div class="pm-card">
                                <div class="pm-card-header">
                                    <i class="mdi mdi-account-outline pm-card-header-icon" style="color: #2449d8;"></i>
                                    <span>Pastor Information</span>
                                </div>
                                <div class="pm-card-body">
                                    <div class="pm-field">
                                        <label class="pm-label">Pastor Name</label>
                                        <div class="pm-input-group">
                                            <div class="pm-input-wrap">
                                                <i class="mdi mdi-account-outline pm-input-icon"></i>
                                                <input
                                                    id="pastorName"
                                                    type="text"
                                                    class="pm-input"
                                                    placeholder="Enter pastor's full name"
                                                    value="{{ $pastorMessage && $pastorMessage->member ? $pastorMessage->member->full_name : '' }}"
                                                    readonly>
                                            </div>

                                            <button
                                                type="button"
                                                id="openPastorModal"
                                                class="btn btn-outline-success">
                                                <i class="mdi mdi-plus"></i>
                                                Add
                                            </button>
                                        </div>
                                    </div>
                                    <div class="pm-field">
                                        <label class="pm-label">Position</label>
                                        <div class="pm-input-wrap">
                                            <i class="mdi mdi-briefcase-outline pm-input-icon"></i>
                                            <input id="pastorPosition" type="text" class="pm-input" placeholder="e.g. Senior Pastor" value="{{ $pastorMessage && $pastorMessage->member ? $pastorMessage->member->position : '' }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Publishing Status --}}
                            <div class="pm-card pm-card-status">
                                <div id="statusAccent" class="pm-card-accent" style="background: {{ $pastorMessage ? '#28a745' : '#8898aa' }};"></div>
                                <div class="pm-card-inner">
                                    <div class="pm-card-header">
                                        <i class="mdi mdi-cloud-check-outline pm-card-header-icon" style="color: #28a745;"></i>
                                        <span>Publishing Status</span>
                                    </div>
                                    <div class="pm-card-body">
                                        <div class="pm-status-row">
                                            <span class="pm-status-label">Status</span>
                                            <span id="statusBadge" class="pm-status-badge {{ $pastorMessage ? 'pm-status-published' : 'pm-status-not-published' }}">
                                                <span class="pm-status-dot"></span> {{ $pastorMessage ? 'Published' : 'Not Published' }}
                                            </span>
                                        </div>
                                        <div class="pm-status-row">
                                            <span class="pm-status-label">Last Updated</span>
                                            <span id="statusDate" class="pm-status-value">{{ $pastorMessage && $pastorMessage->updated_at ? $pastorMessage->updated_at->format('F d, Y h:i A') : '—' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Message Editor --}}
                        <div class="pm-card">
                            <div class="pm-card-header">
                                <i class="mdi mdi-text-box-outline pm-card-header-icon" style="color: #ff8a00;"></i>
                                <span>Message</span>
                                <span class="pm-card-header-badge">Rich Text</span>
                            </div>
                            <div class="pm-card-body pm-editor-body">
                                <label class="pm-label">Title</label>

                                <textarea
                                    id="messageTitle"
                                    name="title"
                                    class="pm-editor pm-editor-title"
                                    rows="2"
                                    placeholder="Write the title here. This will be displayed on the church website homepage...">{{ $pastorMessage->title ?? '' }}</textarea>
                            </div>

                            <div class="pm-card-body pm-editor-body">
                                <label class="pm-label">Content</label>

                                <textarea
                                    id="messageContent"
                                    name="content"
                                    class="pm-editor"
                                    rows="14"
                                    placeholder="Write the pastor's message here. This will be displayed on the church website homepage...">{{ $pastorMessage->content ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: PREVIEW COLUMN --}}
                    <div class="pm-preview-col">
                        <div class="pm-card pm-preview-card">
                            <div class="pm-card-header">
                                <i class="mdi mdi-eye-outline pm-card-header-icon" style="color: #8e3dff;"></i>
                                <span>Live Preview</span>
                            </div>
                            <div class="pm-card-body pm-preview-body">
                                <div class="pm-preview-photo">
                                    <img id="previewPhoto" src="" alt="Pastor Photo" style="display:none;">
                                    <i id="previewPhotoIcon" class="mdi mdi-account"></i>
                                </div>
                                <h4 id="previewName" class="pm-preview-name">Pastor Name</h4>
                                <span id="previewPosition" class="pm-preview-position">Senior Pastor</span>
                                <div class="pm-preview-divider"></div>
                                <p id="previewTitle" class="pm-preview-message">Message preview will appear here once you start typing. The content will be displayed exactly as shown on the public website.</p>
                            </div>
                        </div>

                        {{-- ACTION BUTTONS --}}
                        <div class="pm-card">
                            <div class="pm-card-header">
                            <i class="mdi mdi-content-save-outline pm-card-header-icon" style="color:#28a745;"></i>
                                <span>Actions</span>
                            </div>
                            <div class="pm-card-body">
                                <div class="pm-actions">
                                    <a href="{{ route('website-management.index') }}"
                                        class="btn btn-outline-secondary">
                                            <i class="mdi mdi-close me-1"></i>
                                            Cancel
                                    </a>

                                    <button type="submit" class="btn btn-success">
                                        <i class="mdi mdi-content-save-outline me-1"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
            </div>

        </div>
    </div>
</div>

{{-- Vue Pastor Selector Modal Mount --}}
<div id="pastor-modal-app"></div>

@push('scripts')
<script>
let selectedOrganization = '';
document.addEventListener('DOMContentLoaded', function() {

    const nameInput = document.getElementById('pastorName');
    const positionInput = document.getElementById('pastorPosition');
    const titleInput = document.getElementById('messageTitle');
    const messageInput = document.getElementById('messageContent');

    const previewName = document.getElementById('previewName');
    const previewPosition = document.getElementById('previewPosition');
    const previewTitle = document.getElementById('previewTitle');
    const previewPhoto = document.getElementById('previewPhoto');
    const previewPhotoIcon = document.getElementById('previewPhotoIcon');

    let isPublished = false;

    function setStatus(status) {

        const badge = document.getElementById('statusBadge');
        const accent = document.getElementById('statusAccent');
        if (!badge) return;

        switch (status) {

            case 'draft':
                badge.className = 'pm-status-badge pm-status-draft';
                badge.innerHTML = '<span class="pm-status-dot"></span> Draft';
                if (accent) accent.style.background = '#ff8a00';
                isPublished = false;
                break;

            case 'published':
                badge.className = 'pm-status-badge pm-status-published';
                badge.innerHTML = '<span class="pm-status-dot"></span> Published';
                if (accent) accent.style.background = '#28a745';
                isPublished = true;
                break;

            default:
                badge.className = 'pm-status-badge pm-status-not-published';
                badge.innerHTML = '<span class="pm-status-dot"></span> Not Published';
                if (accent) accent.style.background = '#8898aa';
                isPublished = false;
        }
    }

    function updatePreview() {
        if (previewName) {
            previewName.textContent =
                nameInput.value.trim() || 'Pastor Name';
        }

        const position = positionInput.value.trim();

        let info = '';

        if (selectedOrganization && position) {
            info = `${selectedOrganization} • ${position}`;
        } else if (selectedOrganization) {
            info = selectedOrganization;
        } else if (position) {
            info = position;
        } else {
            info = 'Senior Pastor';
        }

        if (previewPosition) {
            previewPosition.textContent = info;
        }

        if (previewTitle) {
            previewTitle.textContent =
                titleInput.value.trim() ||
                'Message preview will appear here once you start typing.';
        }
    }

    // Set initial publishing status based on server state
    @if($pastorMessage)
        setStatus('published');
    @else
        setStatus('not-published');
    @endif

    // Live preview on input
    [nameInput, positionInput, titleInput, messageInput].forEach(function(input) {

        if (!input) return;

        input.addEventListener('input', function() {

            updatePreview();
            setStatus('draft');

        });

    });

    // Open pastor selector modal
    var addBtn = document.getElementById('openPastorModal');
    if (addBtn) {
        addBtn.addEventListener('click', function() {
            window.dispatchEvent(new Event('open-pastor-selector'));
        });
    }

    // Handle pastor selection from Vue modal
    window.addEventListener('pastor-selected', function(e) {
        var pastor = e.detail;
        if (!pastor) return;

        if (nameInput) nameInput.value = pastor.name || '';
        if (positionInput) positionInput.value = pastor.position || '';

        // Set the hidden member_id for form submission
        var memberIdInput = document.getElementById('pastorMemberId');
        if (memberIdInput) memberIdInput.value = pastor.id || '';

        if (previewPhoto && pastor.photo) {
            previewPhoto.src = '/uploads/' + pastor.photo;
            previewPhoto.style.display = 'block';
            if (previewPhotoIcon) previewPhotoIcon.style.display = 'none';
        } else {
            if (previewPhoto) previewPhoto.style.display = 'none';
            if (previewPhotoIcon) previewPhotoIcon.style.display = 'block';
        }

        updatePreview();

        if (window.toast) {
            window.toast.success('Pastor Selected', (pastor.name || 'Leader') + ' has been selected.');
        }
    });
});
</script>
@endpush

@endsection
