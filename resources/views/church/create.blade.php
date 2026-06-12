@extends('layout')

@section('content')

<div class="col-md-9 col-lg-8 mx-auto">

    {{-- BREADCRUMB --}}
    <div class="church-breadcrumb mb-3">
        <a href="{{ route('church.index') }}" class="church-breadcrumb-link">
            <i class="mdi mdi-church me-1"></i>Church Registration
        </a>
        <i class="mdi mdi-chevron-right mx-1 church-breadcrumb-sep"></i>
        <span class="church-breadcrumb-current">Add New Church</span>
    </div>

    <div class="church-form-card">

        {{-- CARD HEADER --}}
        <div class="church-form-header">
            <div class="church-form-header-icon">
                <i class="mdi mdi-domain-plus"></i>
            </div>
            <div>
                <h4 class="church-form-title mb-0">Add New Church</h4>
                <p class="church-form-subtitle mb-0">Fill in the details to register a new church</p>
            </div>
        </div>

        <div class="church-form-body">
            <form action="{{ route('church.store') }}" method="POST">
                @csrf

                {{-- SECTION: CHURCH INFO --}}
                <div class="church-form-section">
                    <div class="church-section-label">
                        <span class="church-section-num">1</span>
                        <span>Church Information</span>
                    </div>

                    <div class="row g-3 mt-1">
                        <div class="col-md-7">
                            <label class="church-label">Church Name <span class="text-danger">*</span></label>
                            <div class="church-input-wrap">
                                <i class="mdi mdi-domain church-input-icon"></i>
                                <input type="text"
                                       name="church_name"
                                       class="form-control church-input"
                                       placeholder="e.g. Central Adventist Church"
                                       required>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="church-label">Cluster <span class="text-danger">*</span></label>
                            <div class="church-input-wrap">
                                <i class="mdi mdi-layers-outline church-input-icon"></i>
                                <select name="cluster" class="form-select church-select" required>
                                    <option value="">— Select Cluster —</option>
                                    <option value="Cluster 1">Cluster 1</option>
                                    <option value="Cluster 2">Cluster 2</option>
                                    <option value="Cluster 3">Cluster 3</option>
                                    <option value="Cluster 4">Cluster 4</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SECTION: ADDRESS --}}
                <div class="church-form-section">
                    <div class="church-section-label">
                        <span class="church-section-num">2</span>
                        <span>Address Information</span>
                    </div>
                    <div class="mt-2">
                        @livewire('address-picker')
                    </div>
                </div>

                <div class="church-form-actions">
                    <a href="{{ route('church.index') }}" class="btn btn-outline-secondary me-2">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-outline-success">
                        Save Church
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
