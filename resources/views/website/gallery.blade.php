@extends('website.layout')
@section('title', 'Gallery - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>Photo Gallery</h1><p>Capturing moments of faith, fellowship, and service.</p></div></section>

<section class="ws-section">
    <div class="container">
        <div class="row g-4">
            @forelse($albums as $album)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="ws-gallery-card-v2">
                    {{-- Cover Image Grid --}}
                    <div class="ws-gallery-cover" style="background: linear-gradient(135deg, {{ $album->gradient_from }}30, {{ $album->gradient_to }}30);">
                        <div class="ws-gallery-cover-grid">
                            @foreach($album->photos->take(4) as $photo)
                            <div class="ws-gallery-cover-thumb" style="background: linear-gradient(135deg, {{ $album->gradient_from }}50, {{ $album->gradient_to }}50);">
                                <img src="{{ asset('uploads/gallery/' . $photo->filename) }}" alt="" loading="lazy">
                            </div>
                            @endforeach
                            @for($i = $album->photos->count(); $i < 4; $i++)
                            <div class="ws-gallery-cover-thumb ws-gallery-cover-empty" style="background: linear-gradient(135deg, {{ $album->gradient_from }}30, {{ $album->gradient_to }}30);"></div>
                            @endfor
                        </div>
                        {{-- Photo Count Badge --}}
                        <span class="ws-gallery-count-badge"><i class="mdi mdi-image-multiple-outline"></i> {{ $album->photos_count }} Photos</span>
                    </div>

                    {{-- Card Body --}}
                    <div class="ws-gallery-card-v2-body">
                        {{-- Icon Badge --}}
                        <div class="ws-gallery-icon-badge" style="background: {{ $album->gradient_from }}20; color: {{ $album->gradient_from }};">
                            <i class="mdi {{ $album->icon }}"></i>
                        </div>

                        <h5 class="ws-gallery-card-v2-title">{{ $album->title }}</h5>
                        <p class="ws-gallery-card-v2-desc">{{ $album->description }}</p>

                        {{-- View Gallery Button (unchanged) --}}
                        <a href="{{ route('website.gallery.album', $album->id) }}" class="ws-gallery-card-btn">
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
