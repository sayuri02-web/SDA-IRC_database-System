@extends('website.layout')
@section('title', 'Gallery - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>Photo Gallery</h1><p>Capturing moments of faith, fellowship, and service.</p></div></section>

<section class="ws-section">
    <div class="container">
        <div class="row g-4">
            @forelse($albums as $album)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="ws-gallery-card">
                    {{-- TOP: Gradient + Icon --}}
                    <div class="ws-gallery-card-top" style="background: linear-gradient(135deg, {{ $album->gradient_from }}, {{ $album->gradient_to }});">
                        <i class="mdi {{ $album->icon }} ws-gallery-card-icon"></i>
                        <span class="ws-gallery-card-badge"><i class="mdi mdi-camera-outline"></i> Gallery</span>
                    </div>

                    {{-- MIDDLE: Content --}}
                    <div class="ws-gallery-card-body">
                        <h5 class="ws-gallery-card-title">{{ $album->title }}</h5>
                        <p class="ws-gallery-card-desc">{{ $album->description }}</p>
                    </div>

                    {{-- BOTTOM: Button --}}
                    <div class="ws-gallery-card-footer">
                        <a href="#" class="ws-gallery-card-btn">
                            <i class="mdi mdi-camera-outline"></i> View Gallery <i class="mdi mdi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="mdi mdi-image-filter-hdr" style="font-size:48px; color:#d0d5dc;"></i>
                <p class="text-muted mt-3">No gallery albums available yet. Check back soon!</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
