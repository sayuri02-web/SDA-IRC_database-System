@extends('layout')

@section('content')

<div class="col-md-9 col-lg-8 mx-auto">

    {{-- BREADCRUMB --}}
    <div class="church-breadcrumb mb-3">
        <a href="{{ route('church.index') }}" class="church-breadcrumb-link">
            <i class="mdi mdi-church me-1"></i>Church Registration
        </a>
        <i class="mdi mdi-chevron-right mx-1 church-breadcrumb-sep"></i>
        <span class="church-breadcrumb-current">Edit Church</span>
    </div>

    <div class="church-form-card">

        {{-- CARD HEADER --}}
        <div class="church-form-header">
            <div class="church-form-header-icon" style="background: linear-gradient(135deg,#2449d8,#5c7cfa);">
                <i class="mdi mdi-domain"></i>
            </div>
            <div>
                <h4 class="church-form-title mb-0">Edit Church</h4>
                <p class="church-form-subtitle mb-0">Update the details for <strong>{{ $church->church_name }}</strong></p>
            </div>
        </div>

        <div class="church-form-body">
            <form action="{{ route('church.update', $church->id) }}" method="POST">
                @csrf
                @method('PUT')

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
                                       value="{{ $church->church_name }}"
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
                                    @foreach(['Cluster 1','Cluster 2','Cluster 3','Cluster 4'] as $cl)
                                    <option value="{{ $cl }}" {{ $church->cluster === $cl ? 'selected' : '' }}>
                                        {{ $cl }}
                                    </option>
                                    @endforeach
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
                        @livewire('address-picker', ['churchId' => $church->id], key($church->id))
                    </div>
                </div>

                <div class="church-form-actions">
                    <a href="{{ route('church.index') }}" class="btn btn-outline-secondary me-2">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-outline-success">
                        Update Church
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
