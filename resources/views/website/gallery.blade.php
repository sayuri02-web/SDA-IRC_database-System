@extends('website.layout')
@section('title', 'Gallery - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>Photo Gallery</h1><p>Capturing moments of faith, fellowship, and service.</p></div></section>

<section class="ws-section">
    <div class="container">
        @php
        $albums = [
            ['icon' => 'mdi-church', 'category' => 'Worship', 'title' => 'Sabbath Worship', 'desc' => 'Capturing divine moments of praise and worship during our Sabbath services.', 'gradient' => 'linear-gradient(135deg, #667eea, #764ba2)'],
            ['icon' => 'mdi-account-group', 'category' => 'Youth', 'title' => 'Youth Activities', 'desc' => 'Highlights from youth camps, fellowship events, and community service projects.', 'gradient' => 'linear-gradient(135deg, #11998e, #38ef7d)'],
            ['icon' => 'mdi-water', 'category' => 'Sacrament', 'title' => 'Baptisms', 'desc' => 'Celebrating new beginnings as members commit their lives to Christ.', 'gradient' => 'linear-gradient(135deg, #4facfe, #00f2fe)'],
            ['icon' => 'mdi-hand-heart', 'category' => 'Outreach', 'title' => 'Community Outreach', 'desc' => 'Serving our community through feeding programs, health fairs, and education.', 'gradient' => 'linear-gradient(135deg, #fa709a, #fee140)'],
            ['icon' => 'mdi-star-outline', 'category' => 'Events', 'title' => 'Special Programs', 'desc' => 'Memorable moments from crusades, concerts, and church celebrations.', 'gradient' => 'linear-gradient(135deg, #f093fb, #f5576c)'],
            ['icon' => 'mdi-music-note', 'category' => 'Ministry', 'title' => 'Music Ministry', 'desc' => 'Our choir, praise team, and musicians lifting hearts through song.', 'gradient' => 'linear-gradient(135deg, #7f53ac, #647dee)'],
        ];
        @endphp

        <div class="row g-4">
            @foreach($albums as $album)
            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="ws-gallery-card">
                    {{-- TOP: Icon Area --}}
                    <div class="ws-gallery-card-top" style="background: {{ $album['gradient'] }};">
                        <i class="mdi {{ $album['icon'] }} ws-gallery-card-icon"></i>
                        <span class="ws-gallery-card-badge"><i class="mdi mdi-camera-outline"></i> Gallery</span>
                    </div>

                    {{-- MIDDLE: Content --}}
                    <div class="ws-gallery-card-body">
                        <span class="ws-gallery-card-category">{{ $album['category'] }}</span>
                        <h5 class="ws-gallery-card-title">{{ $album['title'] }}</h5>
                        <p class="ws-gallery-card-desc">{{ $album['desc'] }}</p>
                    </div>

                    {{-- BOTTOM: Button --}}
                    <div class="ws-gallery-card-footer">
                        <a href="#" class="ws-gallery-card-btn">
                            <i class="mdi mdi-camera-outline"></i> View Gallery <i class="mdi mdi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
