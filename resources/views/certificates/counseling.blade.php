@extends('layout')

@section('content')

<div class="container mt-4">
    <div class="church-breadcrumb mb-3">
        <a href="{{ route('certificates.index') }}" class="church-breadcrumb-link">
            <i class="mdi mdi-file-document-outline me-1"></i>
            Certifications
        </a>
        <i class="mdi mdi-chevron-right mx-1 church-breadcrumb-sep"></i>
        <span class="church-breadcrumb-current">
            Generate Counseling Certificate
        </span>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-4">

            <div class="text-center mb-3">
                <h2 class="fw-bold">Counseling Certificate</h2>
                <p class="text-muted">Review and complete certificate information</p>
            </div>

            <form method="POST" action="{{ route('certificates.counseling.print') }}" target="_blank">
                @csrf
                <input type="hidden" name="member_id" value="{{ $member->id }}">

                {{-- MEMBER INFO --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Member's Full Name</label>
                        <input type="text" class="form-control" value="{{ trim($member->first_name . ' ' . ($member->middle_initial ? $member->middle_initial . ' ' : '') . $member->last_name . ' ' . ($member->suffix ?? '')) }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Partner / Spouse Name (if applicable)</label>
                        <input type="text" name="partner_name" class="form-control" placeholder="Leave blank if not applicable">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Church</label>
                        <input type="text" class="form-control" value="{{ $member->church->church_name ?? 'N/A' }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Gender</label>
                        <input type="text" class="form-control" value="{{ ucfirst($member->gender ?? 'N/A') }}" readonly>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="text-success mb-3">Certificate Details</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Church Name (on Certificate)</label>
                        <input type="text" name="church_name" class="form-control" value="{{ $member->church->church_name ?? '' }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label class="form-label fw-semibold">Purpose of Counseling</label>
                        <textarea name="purpose" class="form-control" rows="3" placeholder="e.g. to maintain their behavior to the church and to the community to insert knowledge of population and control.">to maintain their behavior to the church and to the community to insert knowledge of population and control.</textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Date of Issuance</label>
                        <input type="date" name="issued_date" class="form-control" value="{{ now()->toDateString() }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Pastor / Chairman Name</label>
                        <input type="text" name="chairman_name" class="form-control" placeholder="e.g. Pastor Nestor G. Laurico">
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="mdi mdi-printer me-1"></i>Generate Certificate
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
