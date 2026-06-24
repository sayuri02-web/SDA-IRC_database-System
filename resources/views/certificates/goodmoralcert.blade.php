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
            Generate Good Moral Certificate
        </span>
    </div>

    <div class="card shadow border-0">
        <div class="card-body p-4">

            <div class="text-center mb-3">
                <h2 class="fw-bold">Good Moral Certificate</h2>
                <p class="text-muted">Certificate of good character and moral standing</p>
            </div>

            <form method="POST" action="{{ route('certificates.goodmoral.print') }}" target="_blank">
                @csrf
                <input type="hidden" name="member_id" value="{{ $member->id }}">

                {{-- MEMBER INFO --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Member's Full Name</label>
                        <input type="text" class="form-control" value="{{ trim($member->first_name . ' ' . ($member->middle_initial ? $member->middle_initial . ' ' : '') . $member->last_name . ' ' . ($member->suffix ?? '')) }}" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Gender</label>
                        <input type="text" class="form-control" value="{{ ucfirst($member->gender ?? 'N/A') }}" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Church</label>
                        <input type="text" class="form-control" value="{{ $member->church->church_name ?? 'N/A' }}" readonly>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="text-success mb-3">Certificate Details</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Church Location</label>
                        <input type="text" name="church_location" class="form-control" placeholder="e.g. Buhangin, Davao City">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Date of Issuance</label>
                        <input type="date" name="issued_date" class="form-control" value="{{ now()->toDateString() }}">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Purpose of Request</label>
                        <input type="text" name="purpose" class="form-control" placeholder="e.g. Physician Licensure Examination purposes">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Church Elder Name</label>
                        <input type="text" name="elder_name" class="form-control" placeholder="e.g. Crispin Q. Vesiete Sr.">
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
