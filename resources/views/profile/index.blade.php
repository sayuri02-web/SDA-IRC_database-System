@extends('layout')
@section('content')

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card shadow-sm" style="border-radius: 16px; border: none;">
        <div class="card-body" style="padding: 32px;">

            {{-- Page Title --}}
            <div class="mb-4">
                <h4 class="card-title mb-1" style="font-weight: 700; color: #1a1f36;">My Profile</h4>
                <p class="text-muted" style="font-size: 14px; margin: 0;">View your personal and account information.</p>
            </div>

            {{-- Alert if no member linked --}}
            @if(!$member)
            <div class="alert alert-warning d-flex align-items-center" style="border-radius: 12px; border: none; background: #fff8e1; padding: 16px 20px;">
                <i class="mdi mdi-alert-circle-outline me-2" style="font-size: 22px; color: #f57c00;"></i>
                <div>
                    <strong>No member record linked.</strong>
                    <span class="text-muted d-block" style="font-size: 13px;">No member record is linked to this account. Please contact the system administrator.</span>
                </div>
            </div>
            @endif

            {{-- SECTION 1: Profile Header --}}
            <div class="profile-header-card mb-4">
                <div class="profile-header-left">
                    <div class="profile-avatar">
                        @if($member?->photo && file_exists(public_path('uploads/' . $member->photo)))
                            <img src="{{ asset('uploads/' . $member->photo) }}" alt="Profile Photo">
                        @else
                            <i class="mdi mdi-account"></i>
                        @endif
                    </div>
                    <div class="profile-header-info">
                        <h4 class="profile-name">{{ $member?->full_name ?? $user->name }}</h4>
                        @if($member?->position)
                            <p class="profile-position">{{ $member->position }}</p>
                        @endif
                        @if($member?->organization)
                            <p class="profile-org"><i class="mdi mdi-account-group-outline me-1"></i>{{ $member->organization }}</p>
                        @endif
                        @if($member?->church)
                            <p class="profile-org"><i class="mdi mdi-church me-1"></i>{{ $member->church->church_name ?? '' }}</p>
                        @endif
                    </div>
                </div>
                <div class="profile-header-right">
                    <span class="profile-badge profile-badge-role"><i class="mdi mdi-shield-account-outline me-1"></i>{{ $user->role?->label() ?? 'User' }}</span>
                    <span class="profile-badge profile-badge-active"><i class="mdi mdi-check-circle-outline me-1"></i>Active</span>
                    <a href="#change-password" class="btn btn-outline-success btn-sm profile-change-pw-btn">
                        <i class="mdi mdi-lock-outline me-1"></i>Change Password
                    </a>
                </div>
            </div>

            {{-- SECTION 2: Personal Information --}}
            @if($member)
            <div class="profile-section">
                <div class="profile-section-header">
                    <i class="mdi mdi-account-outline"></i>
                    <h5>Personal Information</h5>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>First Name</label>
                            <p>{{ $member->first_name ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Middle Initial</label>
                            <p>{{ $member->middle_initial ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Last Name</label>
                            <p>{{ $member->last_name ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Suffix</label>
                            <p>{{ $member->suffix ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Gender</label>
                            <p>{{ $member->gender ? ucfirst($member->gender) : '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Birthdate</label>
                            <p>{{ $member->birthdate ? \Carbon\Carbon::parse($member->birthdate)->format('F d, Y') : '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-field">
                            <label>Birthplace</label>
                            <p>{{ $member->birthplace ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-field">
                            <label>Mobile Number</label>
                            <p>{{ $member->mobile ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="profile-field">
                            <label>Address</label>
                            <p>{{ trim(collect([$member->street, $member->barangay, $member->city, $member->province, $member->region])->filter()->implode(', ')) ?: '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Church Information --}}
            <div class="profile-section">
                <div class="profile-section-header">
                    <i class="mdi mdi-church"></i>
                    <h5>Church Information</h5>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Church</label>
                            <p>{{ $member->church?->church_name ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Cluster</label>
                            <p>{{ $member->church?->cluster ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Organization</label>
                            <p>{{ $member->organization ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Position</label>
                            <p>{{ $member->position ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Baptism Date</label>
                            <p>{{ $member->baptism_date ? \Carbon\Carbon::parse($member->baptism_date)->format('F d, Y') : '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Baptism Place</label>
                            <p>{{ $member->baptism_place ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="profile-field">
                            <label>Officiating Minister</label>
                            <p>{{ $member->officiating_minister ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- SECTION 4: Account Information --}}
            <div class="profile-section">
                <div class="profile-section-header">
                    <i class="mdi mdi-shield-account-outline"></i>
                    <h5>Account Information</h5>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Username</label>
                            <p>{{ $user->username ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Email</label>
                            <p>{{ $user->email ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Role</label>
                            <p>{{ $user->role?->label() ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Account Status</label>
                            <p><span class="badge bg-success">Active</span></p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="profile-field">
                            <label>Account Created</label>
                            <p>{{ $user->created_at?->format('F d, Y') ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 5: Change Password --}}
            <div class="profile-section" id="change-password">
                <div class="profile-section-header">
                    <i class="mdi mdi-lock-outline"></i>
                    <h5>Change Password</h5>
                </div>

                <form method="POST" action="{{ url('/profile/password') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3" style="max-width: 500px;">
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size: 13px;">Current Password</label>
                            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" placeholder="Enter current password" style="border-radius: 10px; height: 42px;">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size: 13px;">New Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter new password" style="border-radius: 10px; height: 42px;">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold" style="font-size: 13px;">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm new password" style="border-radius: 10px; height: 42px;">
                        </div>
                        <div class="col-12 d-flex gap-2 mt-3">
                            <a href="{{ url('/profile') }}" class="btn btn-outline-secondary btn-sm px-4" style="border-radius: 8px;">Cancel</a>
                            <button type="submit" class="btn btn-success btn-sm px-4" style="border-radius: 8px;">
                                <i class="mdi mdi-lock-check-outline me-1"></i>Change Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<style>
/* Profile Header Card */
.profile-header-card {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 20px;
    padding: 24px;
    background: linear-gradient(135deg, #f8faf9, #f0f7f2);
    border: 1px solid #e2ede6;
    border-radius: 14px;
}
.profile-header-left {
    display: flex;
    align-items: center;
    gap: 18px;
}
.profile-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: #e9fff3;
    border: 3px solid #28a745;
    box-shadow: 0 4px 16px rgba(40, 167, 69, 0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    flex-shrink: 0;
}
.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.profile-avatar i {
    font-size: 32px;
    color: #28a745;
}
.profile-header-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}
.profile-name {
    font-size: 18px;
    font-weight: 700;
    color: #1a1f36;
    margin: 0;
}
.profile-position {
    font-size: 13px;
    font-weight: 600;
    color: #525f7f;
    margin: 0;
}
.profile-org {
    font-size: 12px;
    color: #8898aa;
    margin: 0;
}
.profile-header-right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.profile-badge {
    font-size: 11px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
}
.profile-badge-role {
    background: #eef4ff;
    color: #2449d8;
}
.profile-badge-active {
    background: #e6f9ed;
    color: #1a8c45;
}
.profile-change-pw-btn {
    border-radius: 8px !important;
    font-size: 12px;
    font-weight: 600;
}

/* Profile Sections */
.profile-section {
    padding: 24px 0;
    border-top: 1px solid #f0f2f5;
}
.profile-section:first-of-type {
    border-top: none;
}
.profile-section-header {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 18px;
}
.profile-section-header i {
    font-size: 22px;
    color: #28a745;
    background: #e9fff3;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.profile-section-header h5 {
    font-size: 15px;
    font-weight: 700;
    color: #1a1f36;
    margin: 0;
}

/* Profile Fields */
.profile-field {
    padding: 10px 14px;
    background: #f8fafc;
    border-radius: 10px;
    border: 1px solid #edf0f5;
    height: 100%;
}
.profile-field label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: #8898aa;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    margin-bottom: 4px;
}
.profile-field p {
    margin: 0;
    font-size: 14px;
    font-weight: 500;
    color: #1a1f36;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-header-card {
        flex-direction: column;
        align-items: flex-start;
    }
    .profile-header-right {
        width: 100%;
    }
    .profile-avatar {
        width: 60px;
        height: 60px;
    }
    .profile-name {
        font-size: 16px;
    }
}
</style>

@endsection
