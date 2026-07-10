@extends('layout')

@section('title', 'User Management')

@section('content')
<div class="col-lg-12 grid-margin stretch-card members-index-wrapper">
    <div class="card shadow-sm border-0" style="border-radius:20px !important; flex:1; display:flex; flex-direction:column;">
        <div class="card-body p-4" style="display:flex; flex-direction:column; flex:1; min-height:0;">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="church-page-icon" style="background: linear-gradient(135deg, #2449d8, #5c7cfa);">
                        <i class="mdi mdi-account-cog"></i>
                    </div>
                    <div>
                        <h3 class="church-page-title mb-0">User Management</h3>
                        <p class="church-page-subtitle mb-0">Manage system users and their roles</p>
                    </div>
                </div>
                <button class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                    <i class="mdi mdi-plus me-1"></i> Create User
                </button>
            </div>

            <hr class="mb-3" style="flex-shrink:0;">

            {{-- TABLE --}}
            <div class="table-scroll-area" style="flex:1; overflow:auto;">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $u)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <x-member-avatar :member="$u->member" :size="36" />
                                    <div>
                                        <strong style="font-size:13px;">{{ $u->member?->full_name ?? $u->name }}</strong>
                                        @if($u->member?->position || $u->member?->organization)
                                            <div style="font-size:11px; color:#8898aa;">{{ collect([$u->member?->position, $u->member?->organization])->filter()->implode(' • ') }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td><code style="font-size:12px;">{{ $u->username }}</code></td>
                            <td style="font-size:12px; color:#525f7f;">{{ $u->email }}</td>
                            <td>
                                <span class="badge {{ $u->role === \App\Enums\UserRole::Admin ? 'bg-primary' : ($u->role === \App\Enums\UserRole::CertificateManager ? 'bg-success' : 'bg-info') }}" style="font-size:11px;">
                                    {{ $u->role->label() }}
                                </span>
                            </td>
                            <td><span class="status-pill baptized-status"><span class="status-dot"></span>Active</span></td>
                            <td style="font-size:12px; color:#8898aa;">{{ $u->created_at?->format('M d, Y') }}</td>
                            <td>
                                @if($u->id !== auth()->id())
                                <div class="d-flex gap-1">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $u->id }}" title="Edit">
                                        <i class="mdi mdi-pencil-outline"></i>
                                    </button>
                                    <form action="{{ route('users.destroy', $u->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" data-delete-confirm data-delete-title="Delete User" data-delete-msg="Delete {{ $u->member?->full_name ?? $u->name }}? This will only remove their system account, not their member record.">
                                            <i class="mdi mdi-delete-outline"></i>
                                        </button>
                                    </form>
                                </div>
                                @else
                                <span class="text-muted" style="font-size:12px;">Current</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No users found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

{{-- CREATE USER MODAL --}}
<div class="modal fade" id="createUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content leaders-modal">
            <div class="leaders-modal-bar" style="background: linear-gradient(to right, #2449d8, #5c7cfa);"></div>
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <h5 class="fw-bold mb-0"><i class="mdi mdi-account-plus-outline me-2" style="color:#2449d8;"></i>Create User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                @if($errors->any())
                <div class="alert alert-danger py-2 px-3" style="border-radius:10px; font-size:13px;">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Select Member <span class="text-danger">*</span></label>
                        <select name="member_id" class="form-select" required style="border-radius:10px; height:42px;">
                            <option value="">— Choose a member —</option>
                            @foreach($availableMembers as $m)
                                <option value="{{ $m->id }}" {{ old('member_id') == $m->id ? 'selected' : '' }}>{{ $m->full_name }}{{ ($m->position || $m->organization) ? ' — ' . collect([$m->position, $m->organization])->filter()->implode(' • ') : '' }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Only members without an existing account are listed.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" placeholder="Enter username" value="{{ old('username') }}" required style="border-radius:10px; height:42px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Enter email (optional)" value="{{ old('email') }}" style="border-radius:10px; height:42px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required style="border-radius:10px; height:42px;">
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="certificate_manager" {{ old('role') === 'certificate_manager' ? 'selected' : '' }}>Certificate Manager</option>
                            <option value="website_manager" {{ old('role') === 'website_manager' ? 'selected' : '' }}>Website Manager</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Password <span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required style="border-radius:10px; height:42px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required style="border-radius:10px; height:42px;">
                    </div>
                    <button type="submit" class="btn btn-success w-100" style="border-radius:10px; height:42px;">
                        <i class="mdi mdi-account-plus-outline me-1"></i> Create User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- EDIT USER MODALS --}}
@foreach($users as $u)
@if($u->id !== auth()->id())
<div class="modal fade" id="editUserModal{{ $u->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content leaders-modal">
            <div class="leaders-modal-bar" style="background: linear-gradient(to right, #28a745, #6dd5a0);"></div>
            <div class="modal-header border-0 px-4 pt-4 pb-2">
                <h5 class="fw-bold mb-0"><i class="mdi mdi-account-edit-outline me-2" style="color:#28a745;"></i>Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body px-4 pb-4">
                <form method="POST" action="{{ route('users.update', $u->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Linked Member</label>
                        <div class="d-flex align-items-center gap-2 p-2" style="background:#f8fafc; border-radius:10px; border:1px solid #edf0f5;">
                            <x-member-avatar :member="$u->member" :size="32" />
                            <div>
                                <strong style="font-size:13px;">{{ $u->member?->full_name ?? $u->name }}</strong>
                                @if($u->member?->position)
                                    <div style="font-size:11px; color:#8898aa;">{{ $u->member->position }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Username <span class="text-danger">*</span></label>
                        <input type="text" name="username" class="form-control" value="{{ $u->username }}" required style="border-radius:10px; height:42px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $u->email }}" style="border-radius:10px; height:42px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Role <span class="text-danger">*</span></label>
                        <select name="role" class="form-select" required style="border-radius:10px; height:42px;">
                            <option value="admin" {{ $u->role === \App\Enums\UserRole::Admin ? 'selected' : '' }}>Admin</option>
                            <option value="certificate_manager" {{ $u->role === \App\Enums\UserRole::CertificateManager ? 'selected' : '' }}>Certificate Manager</option>
                            <option value="website_manager" {{ $u->role === \App\Enums\UserRole::WebsiteManager ? 'selected' : '' }}>Website Manager</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success w-100" style="border-radius:10px; height:42px;">
                        <i class="mdi mdi-content-save-outline me-1"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endsection

@push('scripts')
@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('createUserModal'));
        modal.show();
    });
</script>
@endif
@endpush
