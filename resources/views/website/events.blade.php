@extends('website.layout')
@section('title', 'Events - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>Church Events</h1><p>Join us for worship, fellowship, and outreach.</p></div></section>

<section class="ws-section">
    <div class="container">
        <div class="row g-4">
            @forelse($events as $e)
            <div class="col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="ws-event-card ws-event-card-lg">
                    <div class="ws-event-date"><i class="mdi mdi-calendar"></i> {{ $e->event_date ? $e->event_date->format('M d, Y') : '—' }}</div>
                    <h4 class="ws-event-title">{{ $e->title }}</h4>
                    <p class="ws-event-desc">{{ $e->description }}</p>
                    @if($e->event_time)
                    <span class="ws-event-time"><i class="mdi mdi-clock-outline me-1"></i>{{ $e->event_time }}</span>
                    @endif
                    @if($e->location)
                    <span class="ws-event-time" style="margin-left:12px;"><i class="mdi mdi-map-marker-outline me-1"></i>{{ $e->location }}</span>
                    @endif
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="mdi mdi-calendar-blank-outline" style="font-size:48px; color:#d0d5dc;"></i>
                <p class="text-muted mt-3">No events available at this time. Check back soon!</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
