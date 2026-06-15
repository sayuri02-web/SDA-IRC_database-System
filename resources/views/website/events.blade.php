@extends('website.layout')
@section('title', 'Events - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>Church Events</h1><p>Join us for worship, fellowship, and outreach.</p></div></section>

<section class="ws-section">
    <div class="container">
        <div class="row g-4">
            @php
            $events = [
                ['title' => 'Sabbath Worship', 'date' => 'Every Saturday', 'desc' => 'Weekly divine worship service for all members and visitors.', 'time' => '9:00 AM - 12:00 PM'],
                ['title' => 'Youth Camp 2026', 'date' => 'Jun 28 - Jul 2, 2026', 'desc' => 'Annual youth camping and spiritual retreat.', 'time' => 'Full Day'],
                ['title' => 'Evangelistic Crusade', 'date' => 'Jul 12 - 26, 2026', 'desc' => 'Two-week evangelistic outreach program.', 'time' => '6:00 PM Nightly'],
                ['title' => 'Community Outreach', 'date' => 'Jul 5, 2026', 'desc' => 'Community feeding program and health education.', 'time' => '7:00 AM - 12:00 PM'],
                ['title' => 'Family Retreat', 'date' => 'Aug 15 - 17, 2026', 'desc' => 'Weekend retreat for families with workshops and bonding activities.', 'time' => 'Full Weekend'],
            ];
            @endphp
            @foreach($events as $e)
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="ws-event-card ws-event-card-lg">
                    <div class="ws-event-date"><i class="mdi mdi-calendar"></i> {{ $e['date'] }}</div>
                    <h4 class="ws-event-title">{{ $e['title'] }}</h4>
                    <p class="ws-event-desc">{{ $e['desc'] }}</p>
                    <span class="ws-event-time"><i class="mdi mdi-clock-outline me-1"></i>{{ $e['time'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
