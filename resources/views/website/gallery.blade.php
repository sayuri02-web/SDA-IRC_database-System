@extends('website.layout')
@section('title', 'Gallery - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>Photo Gallery</h1><p>Capturing moments of faith, fellowship, and service.</p></div></section>

<section class="ws-section">
    <div class="container">
        @php
        $categories = ['Sabbath Worship', 'Youth Activities', 'Baptisms', 'Community Outreach', 'Special Programs'];
        $delay = 0;
        @endphp
        <div class="row g-3">
            @foreach($categories as $cat)
            @for($i = 0; $i < 2; $i++)
            <div class="col-md-4 col-6" data-aos="fade-up" data-aos-delay="{{ $delay * 60 }}">
                <div class="ws-gallery-item">
                    <div class="ws-gallery-placeholder"><i class="mdi mdi-image"></i></div>
                    <div class="ws-gallery-label">{{ $cat }}</div>
                </div>
            </div>
            @php $delay++; @endphp
            @endfor
            @endforeach
        </div>
    </div>
</section>

@endsection
