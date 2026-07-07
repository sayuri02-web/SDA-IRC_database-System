@extends('website.layout')
@section('title', $album->title . ' - Gallery - SDA-IRC Church')
@section('content')

<section class="ws-page-hero" style="background: linear-gradient(135deg, {{ $album->gradient_from }}, {{ $album->gradient_to }});">
    <div class="container" data-aos="fade-up">
        <h1>{{ $album->title }}</h1>
        <p>{{ $album->description ?: 'Browse photos from this album.' }}</p>
    </div>
</section>

<section class="ws-section">
    <div class="container">

        {{-- Back + Info --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <a href="{{ route('website.gallery') }}" class="ws-btn ws-btn-outline" style="border:1.5px solid #e2e8f0; color:#525f7f; padding:10px 20px; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600; transition:0.2s;">
                <i class="mdi mdi-arrow-left me-1"></i> Back to Gallery
            </a>
            <span style="font-size:14px; color:#8898aa; font-weight:500;">
                <i class="mdi mdi-camera-outline me-1"></i> {{ $album->photos_count }} Photos
            </span>
        </div>

        {{-- Photo Grid --}}
        @if($photos->count() > 0)
        <div class="row g-3">
            @foreach($photos as $photo)
            <div class="col-lg-3 col-md-4 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="ws-album-photo">
                    <img src="{{ asset('uploads/gallery/' . $photo->filename) }}" alt="{{ $photo->caption ?: $album->title }}">
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-5">
            <i class="mdi mdi-image-outline" style="font-size:56px; color:#d0d5dc;"></i>
            <p class="text-muted mt-3" style="font-size:15px;">No photos have been uploaded to this album yet.</p>
        </div>
        @endif

    </div>
</section>

@endsection
