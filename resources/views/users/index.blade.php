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
                            <th>Username</th>
                            <th>Full Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td><strong>{{ $user->username }}</strong></td>
                            <td>{{ $user->name }}</td>
                            <td>
                                <span class="badge {{ $user->role === \App\Enums\UserRole::Admin ? 'bg-primary' : ($user->role === \App\Enums\UserRole::CertificateManager ? 'bg-success' : 'bg-info') }}" style="font-size:11px;">
                                    {{ $user->role->label() }}
                                </span>
                            </td>
                            <td><span class="status-pill baptized-status"><span class="status-dot"></span>Active</span></td>
                            <td>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-delete-confirm data-delete-title="Delete User" data-delete-msg="Delete {{ $user->name }}? This cannot be undone.">
                                        <i class="mdi mdi-delete-outline"></i>
                                    </button>
                                </form>
                                @else
                                <span class="text-muted" style="font-size:12px;">Current</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No users found.</td>
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
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Full Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Enter username" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="admin">Admin</option>
                            <option value="certificate_manager">Certificate Manager</option>
                            <option value="website_manager">Website Manager</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Min 6 characters" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:13px;">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="mdi mdi-account-plus-outline me-1"></i> Create User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
