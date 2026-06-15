@extends('website.layout')
@section('title', 'Ministries - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>Our Ministries</h1><p>Serving God through diverse ministries.</p></div></section>

<section class="ws-section">
    <div class="container">
        <div class="row g-4">
            @php
            $ministries = [
                ['icon' => 'mdi-account-group', 'name' => 'Youth Ministry', 'desc' => 'Empowering young people to live for Christ through fellowship, service, and spiritual growth programs.'],
                ['icon' => 'mdi-compass-outline', 'name' => 'Pathfinders', 'desc' => 'Character-building outdoor activities and community service for ages 10-15.'],
                ['icon' => 'mdi-star-outline', 'name' => 'Adventurers', 'desc' => 'Fun, faith-based activities for children ages 6-9, nurturing their love for God.'],
                ['icon' => 'mdi-heart-outline', 'name' => "Women's Ministry", 'desc' => "Supporting and empowering women through Bible study, mentoring, and fellowship."],
                ['icon' => 'mdi-shield-account', 'name' => "Men's Ministry", 'desc' => 'Building godly men through discipleship, accountability, and service projects.'],
                ['icon' => 'mdi-music-note', 'name' => 'Music Ministry', 'desc' => 'Leading worship through choir, praise team, and instrumental music.'],
                ['icon' => 'mdi-home-heart', 'name' => 'Family Ministry', 'desc' => 'Strengthening families through counseling, retreats, and enrichment programs.'],
            ];
            @endphp
            @foreach($ministries as $m)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="ws-ministry-card">
                    <div class="ws-ministry-icon"><i class="mdi {{ $m['icon'] }}"></i></div>
                    <h5>{{ $m['name'] }}</h5>
                    <p>{{ $m['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
