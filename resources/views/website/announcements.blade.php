@extends('website.layout')
@section('title', 'Announcements - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>Announcements</h1><p>Stay updated with the latest church news.</p></div></section>

<section class="ws-section">
    <div class="container">
        <div class="row g-4">
            @php
            $announcements = [
                ['title' => 'Upcoming Baptism', 'date' => 'June 22, 2026', 'desc' => 'We celebrate as new members commit their lives to Christ through baptism this coming Sabbath.'],
                ['title' => 'Prayer Meeting Schedule', 'date' => 'June 18, 2026', 'desc' => 'Wednesday evening prayer meetings resume at 6:30 PM. All are welcome to join.'],
                ['title' => 'District Fellowship', 'date' => 'June 15, 2026', 'desc' => 'Annual district fellowship scheduled for August 3 at the conference center.'],
                ['title' => 'Sabbath School Training', 'date' => 'June 10, 2026', 'desc' => 'Training workshop for Sabbath School teachers. Please register with the SS department.'],
                ['title' => 'Church Building Fund', 'date' => 'June 5, 2026', 'desc' => 'Construction progress update and call for continued support of the building fund.'],
            ];
            @endphp
            @foreach($announcements as $a)
            <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                <div class="ws-announce-card">
                    <h5>{{ $a['title'] }}</h5>
                    <p>{{ $a['desc'] }}</p>
                    <span class="ws-announce-date"><i class="mdi mdi-clock-outline me-1"></i>{{ $a['date'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection
