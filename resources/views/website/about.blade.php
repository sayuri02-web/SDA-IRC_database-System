@extends('website.layout')
@section('title', 'About Us - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>About Us</h1><p>Learn about our history, values, and leadership.</p></div></section>

<section class="ws-section">
    <div class="container">
        {{-- Church History + Image --}}
        <div class="row g-5 align-items-center mb-5">
            <div class="col-md-6" data-aos="fade-right">
                <h2 class="ws-section-title text-start">Church History</h2>
                @if($churchInfo?->church_history)
                    {!! nl2br(e($churchInfo->church_history)) !!}
                @else
                    <p class="text-muted">Church history will be published soon.</p>
                @endif
            </div>
            <div class="col-md-6" data-aos="fade-left">
                @if($churchInfo?->church_image)
                    <img src="{{ asset('uploads/about/' . $churchInfo->church_image) }}" alt="Church" style="width:100%; height:300px; border-radius:16px; object-fit:cover; display:block;">
                @else
                    <div class="ws-about-img-placeholder"><i class="mdi mdi-home"></i></div>
                @endif
            </div>
        </div>

        {{-- Mission & Vision --}}
        @if($churchInfo?->mission || $churchInfo?->vision)
        <div class="row g-4 mb-5">
            @if($churchInfo?->mission)
            <div class="col-md-6" data-aos="fade-right">
                <div class="ws-mv-card ws-mv-mission">
                    <div class="ws-mv-icon"><i class="mdi mdi-target"></i></div>
                    <h3>Our Mission</h3>
                    <p>{{ $churchInfo->mission }}</p>
                    <div class="ws-mv-accent"></div>
                </div>
            </div>
            @endif
            @if($churchInfo?->vision)
            <div class="col-md-6" data-aos="fade-left">
                <div class="ws-mv-card ws-mv-vision">
                    <div class="ws-mv-icon"><i class="mdi mdi-eye-outline"></i></div>
                    <h3>Our Vision</h3>
                    <p>{{ $churchInfo->vision }}</p>
                    <div class="ws-mv-accent"></div>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Core Values --}}
        @if($churchInfo?->core_values)
        <div class="text-center mb-4" data-aos="fade-up">
            <span class="ws-section-badge">What We Stand For</span>
            <h2 class="ws-section-title">Core Values</h2>
        </div>
        <div class="row g-4 mb-5">
            @foreach(array_filter(explode("\n", $churchInfo->core_values)) as $value)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="ws-value-card">
                    <i class="mdi mdi-check-decagram"></i>
                    <h5>{{ trim($value) }}</h5>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Meet Our Leaders --}}
        @if($leaders->count() > 0)
        <h2 class="ws-section-title" data-aos="fade-up">Meet Our Leaders</h2>
        <div class="row g-4">
            @foreach($leaders as $leader)
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="ws-leader-card">
                    <div class="ws-leader-avatar">
                        @if($leader->member?->photo)
                            <img src="{{ asset('uploads/' . $leader->member->photo) }}" alt="{{ $leader->member->full_name }}" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                        @else
                            <i class="mdi mdi-account"></i>
                        @endif
                    </div>
                    <h6>{{ $leader->member?->full_name ?? '—' }}</h6>
                    @if($leader->member?->organization)
                        <p>{{ $leader->member->organization }}</p>
                    @endif
                    @if($leader->member?->position)
                        <p>{{ $leader->member->position }}</p>
                    @endif
                    @if($leader->member?->church)
                        <p>{{ $leader->member->church->church_name ?? '' }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

@endsection
