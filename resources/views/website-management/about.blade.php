@extends('layout')

@section('title', 'About Us - Website Management')

@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card shadow-sm border-0" style="border-radius:20px !important; flex:1; display:flex; flex-direction:column;">
        <div class="card-body p-4" style="display:flex; flex-direction:column; flex:1; min-height:0;">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('website-management.index') }}" class="gallery-back-btn">
                        <i class="mdi mdi-arrow-left"></i>
                    </a>
                    <div class="gallery-header-icon" style="background: linear-gradient(135deg, #2e7d32, #66bb6a);">
                        <i class="mdi mdi-information-outline"></i>
                    </div>
                    <div>
                        <h3 class="gallery-page-title">About Us</h3>
                        <p class="gallery-page-subtitle">Manage church information and leadership</p>
                    </div>
                </div>
            </div>

            <hr style="flex-shrink:0; border-color:#f0f4f8; margin-bottom:16px;">

            <div id="about-management-app"></div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
@vite('resources/js/about-management.js')
@endpush
