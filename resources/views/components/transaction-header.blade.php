{{-- Shared Transaction Page Header Component --}}
{{-- Usage: @include('components.transaction-header', ['module' => '...', 'moduleUrl' => '...', 'moduleIcon' => '...', 'title' => '...', 'subtitle' => '...', 'titleIcon' => '...', 'headerColor' => '...']) --}}

<div class="church-breadcrumb mb-3">
    <a href="{{ $moduleUrl }}" class="church-breadcrumb-link">
        <i class="mdi {{ $moduleIcon }} me-1"></i>{{ $module }}
    </a>
    <i class="mdi mdi-chevron-right mx-1 church-breadcrumb-sep"></i>
    <span class="church-breadcrumb-current">{{ $title }}</span>
</div>

<div class="church-form-card">
    <div class="church-form-header" @if(!empty($headerColor)) style="background: {{ $headerColor }};" @endif>
        <div class="church-form-header-icon">
            <i class="mdi {{ $titleIcon }}"></i>
        </div>
        <div>
            <h4 class="church-form-title mb-0">{{ $title }}</h4>
            <p class="church-form-subtitle mb-0">{{ $subtitle }}</p>
        </div>
    </div>
