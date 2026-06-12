@extends('layout')

@section('content')

<div class="col-md-7 col-lg-6 mx-auto">

    {{-- BREADCRUMB --}}
    <div class="church-breadcrumb mb-3">
        <a href="{{ route('certificates.index') }}" class="church-breadcrumb-link">
            <i class="mdi mdi-certificate-outline me-1"></i>Certificates
        </a>
        <i class="mdi mdi-chevron-right mx-1 church-breadcrumb-sep"></i>
        <span class="church-breadcrumb-current">Edit Template</span>
    </div>

    <div class="church-form-card">

        <div class="church-form-header" style="background: linear-gradient(135deg,#2449d8 0%,#5c7cfa 100%);">
            <div class="church-form-header-icon" style="background: rgba(255,255,255,0.2); color: #fff;">
                <i class="mdi mdi-certificate-outline"></i>
            </div>
            <div>
                <h4 class="church-form-title mb-0" style="color:#fff;">Edit Certificate Template</h4>
                <p class="church-form-subtitle mb-0" style="color:rgba(255,255,255,0.8);">
                    Updating: <strong>{{ $certificate->certificate_name }}</strong>
                </p>
            </div>
        </div>

        <div class="church-form-body">
            <form action="{{ route('certificates.update', $certificate->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="church-label">Certificate Name <span class="text-danger">*</span></label>
                    <div class="church-input-wrap">
                        <i class="mdi mdi-certificate-outline church-input-icon"></i>
                        <input type="text"
                               name="certificate_name"
                               class="form-control church-input"
                               value="{{ $certificate->certificate_name }}"
                               required>
                    </div>
                    <small class="text-muted mt-1 d-block">This name will appear on the printed certificate header.</small>
                </div>

                <div class="church-form-actions">
                    <a href="{{ route('certificates.index') }}" class="btn btn-outline-secondary me-2">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-outline-success">
                        Update Template
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
