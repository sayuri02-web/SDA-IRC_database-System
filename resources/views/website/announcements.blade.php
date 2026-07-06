@extends('website.layout')
@section('title', 'Announcements - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>Announcements</h1><p>Stay updated with the latest church news.</p></div></section>

<section class="ws-section">
    <div class="container">
        <div class="row g-4">
            @forelse($announcements as $a)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="ws-announce-card">
                    <h5>{{ $a->title }}</h5>
                    <p>{{ $a->description }}</p>
                    <span class="ws-announce-date"><i class="mdi mdi-clock-outline me-1"></i>{{ $a->updated_at->format('F d, Y') }}</span>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="mdi mdi-bullhorn-variant-outline" style="font-size:48px; color:#d0d5dc;"></i>
                <p class="text-muted mt-3">No announcements available at this time. Check back soon!</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
