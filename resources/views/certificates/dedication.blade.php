@extends('layout')

@section('content')

<div class="container mt-4">
    <div class="church-breadcrumb mb-3">

        <a href="{{ route('certificates.index') }}"
        class="church-breadcrumb-link">
            <i class="mdi mdi-file-document-outline me-1"></i>
            Certifications
        </a>

        <i class="mdi mdi-chevron-right mx-1 church-breadcrumb-sep"></i>

        <span class="church-breadcrumb-current">
            Generate Dedication Certificate
        </span>

    </div>
    <div class="card shadow border-0">
        <div class="card-body p-4">

            <div class="text-center mb-3">
                <h2 class="fw-bold">Dedication Certificate</h2>
                <p class="text-muted">Review and complete certificate information</p>
            </div>

            <form method="POST" action="{{ route('certificates.dedication.print') }}" target="_blank">
                @csrf
                <input type="hidden" name="member_id" value="{{ $member->id }}">

                {{-- CHILD INFO --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Child's Full Name</label>
                        <input type="text" class="form-control" value="{{ trim($member->first_name . ' ' . ($member->middle_initial ? $member->middle_initial . ' ' : '') . $member->last_name . ' ' . ($member->suffix ?? '')) }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Birth Date</label>
                        <input type="date" class="form-control" value="{{ $member->birthdate }}" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Born At (Place)</label>
                        <input type="text" class="form-control" value="{{ $member->birthplace }}" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Father</label>
                        <input type="text" class="form-control" value="{{ $member->father_name }}" readonly>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-semibold">Mother</label>
                        <input type="text" class="form-control" value="{{ $member->mother_name }}" readonly>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="text-success mb-3">Dedication Information</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Church Name</label>
                        <input type="text" name="church_name" class="form-control" value="{{ $member->church->church_name ?? '' }}">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Church Location</label>
                        <input type="text" name="church_location" class="form-control" placeholder="City, Province">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Dedication Date</label>
                        <input type="date" name="dedication_date" class="form-control" value="{{ $member->baptism_date }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Officiating Minister</label>
                        <input type="text" name="officiating_minister" class="form-control" value="{{ $member->officiating_minister }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Chairman of Organization</label>
                        <input type="text" name="chairman" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-semibold">Witnesses</label>

                        <div id="witness-container">
                            <div class="input-group mb-2">
                                <input
                                    type="text"
                                    name="witnesses[]"
                                    class="form-control"
                                    placeholder="Enter witness name">
                            </div>
                        </div>

                        <button
                            type="button"
                            id="addWitness"
                            class="btn btn-outline-primary btn-sm mt-2">
                            <i class="mdi mdi-plus"></i> Add Witness
                        </button>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-5">Generate Certificate</button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection