@extends('website.layout')
@section('title', 'Ministries')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>Our Ministries</h1><p>Serving God through diverse ministries.</p></div></section>

<section class="ws-section">
    <div class="container">
        <div class="row g-4">
            @forelse($ministries as $m)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="ws-ministry-card">
                    <div class="ws-ministry-icon"><i class="mdi {{ $m->icon }}"></i></div>
                    <h5>{{ $m->name }}</h5>
                    <p>{{ $m->description }}</p>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="mdi mdi-account-group-outline" style="font-size:48px; color:#d0d5dc;"></i>
                <p class="text-muted mt-3">No ministries available at this time.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>

@endsection
