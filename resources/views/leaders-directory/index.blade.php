@extends('layout')
@section('content')

<div class="col-lg-12 grid-margin stretch-card leaders-page-wrapper">
    <div class="card shadow-sm border-0 leaders-page-card">
        <div class="card-body p-4 leaders-page-body">

            {{-- BREADCRUMB --}}
            <div class="church-breadcrumb mb-3">
                <a href="{{ url('/members') }}" class="church-breadcrumb-link"><i class="mdi mdi-account-group me-1"></i>Members</a>
                <i class="mdi mdi-chevron-right mx-1 church-breadcrumb-sep"></i>
                <span class="church-breadcrumb-current">Church Leaders Directory</span>
            </div>

            {{-- PAGE HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="church-page-icon" style="background: linear-gradient(135deg, #2449d8, #5c7cfa);"><i class="mdi mdi-account-tie"></i></div>
                    <div>
                        <h3 class="church-page-title mb-0">Church Leaders Directory</h3>
                        <p class="church-page-subtitle mb-0">Manage and view church leadership records</p>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#addOrgModal"><i class="mdi mdi-plus me-1"></i> Add Organization</button>
                    <a href="{{ url('/members') }}" class="btn btn-outline-secondary btn-sm"><i class="mdi mdi-arrow-left me-1"></i> Back</a>
                </div>
            </div>

            {{-- SCROLLABLE CONTENT --}}
            <div class="leaders-scroll-area">
                <div class="row g-3 mb-4">
                    @foreach($organizations as $org)
                    <div class="col-lg-4 col-md-6" id="org-card-{{ $org->id }}">
                        <div class="leaders-org-card">
                            <div class="leaders-org-icon" style="background:{{ $org->bg_color }}; color:{{ $org->color }};"><i class="mdi {{ $org->icon }}"></i></div>
                            <h5 class="leaders-org-name">{{ $org->name }}</h5>
                            <p class="leaders-org-count">{{ $org->membersCount() }} Officers</p>
                            <div class="d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm leaders-view-btn" data-bs-toggle="modal" data-bs-target="#modal-org-{{ $org->id }}"><i class="mdi mdi-eye-outline me-1"></i> View</button>
                                <button class="btn btn-outline-danger btn-sm leaders-action-delete-org" data-id="{{ $org->id }}" data-name="{{ $org->name }}" data-count="{{ $org->membersCount() }}" title="Delete Organization"><i class="mdi mdi-delete-outline me-1"></i> Delete</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ======================== ORGANIZATION MODALS ======================== --}}
@foreach($organizations as $org)
<div class="modal fade" id="modal-org-{{ $org->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content leaders-modal">
            <div class="leaders-modal-bar" style="background: linear-gradient(to right, {{ $org->color }}, {{ $org->color }}88);"></div>
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <div class="leaders-modal-icon" style="background: {{ $org->color }}15; color: {{ $org->color }};"><i class="mdi {{ $org->icon }}"></i></div>
                    <div>
                        <h5 class="fw-bold mb-0">{{ $org->name }}</h5>
                        <p class="text-muted mb-0" style="font-size:13px;">Manage leadership records and assignments</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <div class="mb-3">
                    <a href="{{ url('/members/create?leader=1&organization=' . urlencode($org->name)) }}" class="btn btn-outline-success btn-sm"><i class="mdi mdi-plus me-1"></i> Add Officer</a>
                </div>
                @if(isset($orgMembers[$org->id]) && $orgMembers[$org->id]->count() > 0)
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr><th>Photo</th><th>Full Name</th><th>Position</th><th>Status</th><th>Actions</th></tr>
                    </thead>
                    <tbody>
                        @foreach($orgMembers[$org->id] as $member)
                        <tr id="leader-row-{{ $member->id }}">
                            <td>
                                @if($member->photo)
                                <img src="{{ asset('uploads/' . $member->photo) }}" alt="" style="width:36px; height:36px; border-radius:50%; object-fit:cover;">
                                @else
                                <div class="leaders-avatar-sm">{{ strtoupper(substr($member->first_name, 0, 1)) }}</div>
                                @endif
                            </td>
                            <td><strong>{{ $member->full_name }}</strong></td>
                            <td>{{ $member->position }}</td>
                            <td><span class="leaders-status-badge leaders-status-active">Active</span></td>
                            <td>
                                <a href="{{ url('/members/' . $member->id . '?from=leaders') }}" class="leaders-action-view" title="View Leader"><i class="mdi mdi-eye-outline"></i></a>
                                <a href="{{ url('/members/' . $member->id . '/edit?from=leaders') }}" class="leaders-action-edit" title="Edit Leader"><i class="mdi mdi-pencil-outline"></i></a>
                                <button class="leaders-action-delete" title="Delete" data-id="{{ $member->id }}"><i class="mdi mdi-delete-outline"></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-5">
                    <i class="mdi mdi-account-group-outline" style="font-size:48px; color:#d0d0d0;"></i>
                    <p class="text-muted mt-2">No officers assigned yet.</p>
                    <a href="{{ url('/members/create?leader=1&organization=' . urlencode($org->name)) }}" class="btn btn-outline-success btn-sm"><i class="mdi mdi-plus me-1"></i> Add First Officer</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- ======================== ADD ORGANIZATION MODAL ======================== --}}
<div class="modal fade" id="addOrgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content leaders-modal">
            <div class="leaders-modal-bar"></div>
            <div class="modal-header border-0 px-4 pt-4 pb-0">
                <h5 class="fw-bold mb-0">Add Organization</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form action="{{ route('leaders-directory.store-org') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Organization Name</label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Music Ministry Officers" style="border-radius:10px; height:42px;" required>
                    </div>
                    <button type="submit" class="btn btn-outline-success w-100">Save Organization</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- ======================== DELETE MODALS ======================== --}}

<div class="modal fade" id="deleteLeaderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content leaders-modal">
            <div class="leaders-modal-bar" style="background: linear-gradient(to right, #e53935, #ff6b6b);"></div>
            <div class="modal-body p-4 text-center">
                <i class="mdi mdi-alert-circle-outline" style="font-size:48px; color:#e53935;"></i>
                <h5 class="fw-bold mt-3 mb-2">Delete Member</h5>
                <p class="text-muted mb-4" style="font-size:14px;">Are you sure you want to remove this officer?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger btn-sm px-4" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ======================== DELETE ORGANIZATION MODAL ======================== --}}
<div class="modal fade" id="deleteOrgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content leaders-modal">
            <div class="leaders-modal-bar" style="background: linear-gradient(to right, #e53935, #ff6b6b);"></div>
            <div class="modal-body p-4 text-center">
                <i class="mdi mdi-alert-circle-outline" style="font-size:48px; color:#e53935;"></i>
                <h5 class="fw-bold mt-3 mb-2">Delete Organization</h5>
                <p class="text-muted mb-1" style="font-size:14px;">Are you sure you want to delete <strong id="deleteOrgName"></strong>?</p>
                <p class="text-danger mb-4" style="font-size:13px;" id="deleteOrgWarning"></p>
                <div class="d-flex gap-2 justify-content-center">
                    <button class="btn btn-outline-secondary btn-sm px-4" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger btn-sm px-4" id="confirmDeleteOrgBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // ===== DELETE MEMBER/OFFICER =====
    let deleteId = null;
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.leaders-action-delete');
        if (!btn) return;
        e.preventDefault();
        deleteId = btn.dataset.id;
        new bootstrap.Modal(document.getElementById('deleteLeaderModal')).show();
    });

    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!deleteId) return;
        fetch('/leaders-directory/member/' + deleteId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        }).then(r => r.json()).then(d => {
            if (d.success) location.reload();
        });
    });

    // ===== DELETE ORGANIZATION =====
    let deleteOrgId = null;
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.leaders-action-delete-org');
        if (!btn) return;
        e.preventDefault();
        deleteOrgId = btn.dataset.id;
        document.getElementById('deleteOrgName').textContent = btn.dataset.name;
        const count = parseInt(btn.dataset.count);
        document.getElementById('deleteOrgWarning').textContent = count > 0
            ? 'This will also remove ' + count + ' officer(s) assigned to this organization. This action cannot be undone.'
            : 'This action cannot be undone.';
        new bootstrap.Modal(document.getElementById('deleteOrgModal')).show();
    });

    document.getElementById('confirmDeleteOrgBtn').addEventListener('click', function() {
        if (!deleteOrgId) return;
        fetch('/leaders-directory/organization/' + deleteOrgId, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrfToken }
        }).then(r => r.json()).then(d => {
            if (d.success) location.reload();
        });
    });
});
</script>
@endpush

@endsection
