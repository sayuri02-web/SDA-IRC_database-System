@extends('website.layout')
@section('title', 'About Us - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>About Us</h1><p>Learn about our history, values, and leadership.</p></div></section>

<section class="ws-section">
    <div class="container">
        <div class="row g-5 align-items-center mb-5">
            <div class="col-md-6" data-aos="fade-right">
                <h2 class="ws-section-title text-start">Church History</h2>
                <p>The SDA Inter-Regional Conference was established to serve the growing Adventist community in the region. Through decades of faithful service, our conference has grown from a small group of believers into a vibrant community of churches spread across the region.</p>
                <p>Today, we continue our mission of sharing God's love through community service, education, and spiritual nurturing.</p>
            </div>
            <div class="col-md-6" data-aos="fade-left">
                <div class="ws-about-img-placeholder"><i class="mdi mdi-home"></i></div>
            </div>
        </div>

        <div class="row g-4 mb-5">
            <div class="col-md-4" data-aos="fade-up"><div class="ws-value-card"><i class="mdi mdi-book-open-variant"></i><h5>Biblical Truth</h5><p>Committed to the authority of Scripture in all matters of faith and practice.</p></div></div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100"><div class="ws-value-card"><i class="mdi mdi-hand-heart"></i><h5>Service</h5><p>Dedicated to serving our community with compassion and generosity.</p></div></div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200"><div class="ws-value-card"><i class="mdi mdi-account-group"></i><h5>Fellowship</h5><p>Building meaningful relationships through worship and shared purpose.</p></div></div>
        </div>

        <h2 class="ws-section-title" data-aos="fade-up">Meet Our Leaders</h2>
        <div class="row g-4">
            @foreach(['Pastor' => 'Senior Pastor', 'Head Elder' => 'Church Elder', 'Church Clerk' => 'Record Keeper', 'Treasurer' => 'Financial Officer'] as $role => $title)
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="ws-leader-card">
                    <div class="ws-leader-avatar"><i class="mdi mdi-account"></i></div>
                    <h6>{{ $role }}</h6>
                    <p>{{ $title }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
