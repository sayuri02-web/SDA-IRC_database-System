@extends('website.layout')
@section('title', 'Home - SDA-IRC Church')

@section('content')

{{-- HERO --}}
<section class="ws-hero">
    <div class="ws-hero-bg"></div>
    <div class="ws-hero-overlay"></div>
    <div class="container position-relative">
        <div class="ws-hero-content" data-aos="fade-up" data-aos-duration="1000">
            <span class="ws-hero-badge"><i class="mdi mdi-home me-1"></i>Seventh-day Adventist Church</span>
            <h1 class="ws-hero-title">Welcome to Seventh-day Adventist<br>Inter-Regional Conference</h1>
            <div class="ws-hero-accent"></div>
            <p class="ws-hero-sub">Growing in Faith. Serving the Community. Sharing God's Love with the World.</p>
            <div class="ws-hero-btns">
                <a href="{{ route('website.events') }}" class="ws-btn-hero-primary"><i class="mdi mdi-calendar-heart"></i>Join Us This Sabbath <i class="mdi mdi-arrow-right"></i></a>
            </div>
        </div>
        <div class="ws-hero-scroll" data-aos="fade-up" data-aos-delay="600">
            <i class="mdi mdi-chevron-double-down"></i>
        </div>
    </div>
    {{-- SERVICE TIMES — Glassmorphism Cards --}}
    <div class="ws-hero-services">
        <div class="container">
            <div class="ws-hero-services-inner" data-aos="fade-up" data-aos-delay="400">
                <div class="ws-service-card">
                    <div class="ws-service-icon-wrap">
                        <i class="mdi mdi-book-open-variant"></i>
                    </div>
                    <h4 class="ws-service-title">Sabbath School</h4>
                    <div class="ws-service-divider"></div>
                    <div class="ws-service-detail">
                        <i class="mdi mdi-calendar-outline"></i>
                        <span>Saturday</span>
                    </div>
                    <div class="ws-service-detail">
                        <i class="mdi mdi-clock-outline"></i>
                        <span>8:00 AM</span>
                    </div>
                </div>

                <div class="ws-service-card">
                    <div class="ws-service-icon-wrap">
                        <i class="mdi mdi-home"></i>
                    </div>
                    <h4 class="ws-service-title">Divine Worship</h4>
                    <div class="ws-service-divider"></div>
                    <div class="ws-service-detail">
                        <i class="mdi mdi-calendar-outline"></i>
                        <span>Saturday</span>
                    </div>
                    <div class="ws-service-detail">
                        <i class="mdi mdi-clock-outline"></i>
                        <span>10:00 AM</span>
                    </div>
                </div>

                <div class="ws-service-card">
                    <div class="ws-service-icon-wrap">
                        <i class="mdi mdi-hands-pray"></i>
                    </div>
                    <h4 class="ws-service-title">Sundown Worship</h4>
                    <div class="ws-service-divider"></div>
                    <div class="ws-service-detail">
                        <i class="mdi mdi-calendar-outline"></i>
                        <span>Saturday</span>
                    </div>
                    <div class="ws-service-detail">
                        <i class="mdi mdi-clock-outline"></i>
                        <span>5:30 PM</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- MISSION & VISION --}}
<section class="ws-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="ws-section-badge">Who We Are</span>
            <h2 class="ws-section-title">Our Mission & Vision</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6" data-aos="fade-right" data-aos-duration="800">
                <div class="ws-mv-card ws-mv-mission">
                    <div class="ws-mv-icon"><i class="mdi mdi-target"></i></div>
                    <h3>Our Mission</h3>
                    <p>To proclaim the everlasting gospel to all peoples in preparation for the Second Coming of Jesus Christ, nurturing believers in their walk with God and empowering communities through service and education.</p>
                    <div class="ws-mv-accent"></div>
                </div>
            </div>
            <div class="col-md-6" data-aos="fade-left" data-aos-duration="800">
                <div class="ws-mv-card ws-mv-vision">
                    <div class="ws-mv-icon"><i class="mdi mdi-eye-outline"></i></div>
                    <h3>Our Vision</h3>
                    <p>A transformed community of believers living in harmony with God's will, actively participating in His mission, and reflecting His character of love, justice, and mercy to the world around us.</p>
                    <div class="ws-mv-accent"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- STATISTICS --}}
<section class="ws-stats-section">
    <div class="ws-stats-bg"></div>
    <div class="container position-relative">
        <div class="text-center mb-5" data-aos="fade-up">
            <h2 class="ws-stats-title">God's Work in Numbers</h2>
            <p class="ws-stats-sub">Growing together as a church family</p>
        </div>
        <div class="row g-4">
            <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-delay="0">
                <div class="ws-stat-card">
                    <div class="ws-stat-icon"><i class="mdi mdi-account-group"></i></div>
                    <span class="ws-stat-num">1,245<small>+</small></span>
                    <span class="ws-stat-label">Active Members</span>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-delay="100">
                <div class="ws-stat-card">
                    <div class="ws-stat-icon"><i class="mdi mdi-home-group"></i></div>
                    <span class="ws-stat-num">18</span>
                    <span class="ws-stat-label">Local Churches</span>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-delay="200">
                <div class="ws-stat-card">
                    <div class="ws-stat-icon"><i class="mdi mdi-certificate"></i></div>
                    <span class="ws-stat-num">523<small>+</small></span>
                    <span class="ws-stat-label">Certificates Issued</span>
                </div>
            </div>
            <div class="col-6 col-lg-3" data-aos="zoom-in" data-aos-delay="300">
                <div class="ws-stat-card">
                    <div class="ws-stat-icon"><i class="mdi mdi-calendar-star"></i></div>
                    <span class="ws-stat-num">8</span>
                    <span class="ws-stat-label">Upcoming Events</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- UPCOMING EVENTS --}}
<section class="ws-section">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="ws-section-badge">What's Happening</span>
            <h2 class="ws-section-title">Upcoming Events</h2>
            <p class="ws-section-desc">Join us for worship, fellowship, and community programs.</p>
        </div>
        <div class="row g-4">
            @php
            $events = [
                ['icon' => 'mdi-campfire', 'title' => 'Youth Camp 2026', 'date' => 'Jun 28 - Jul 2', 'desc' => 'Annual spiritual retreat and outdoor adventure for young people.'],
                ['icon' => 'mdi-hand-heart', 'title' => 'Community Outreach', 'date' => 'Jul 5, 2026', 'desc' => 'Feeding program, health screening, and community service.'],
                ['icon' => 'mdi-microphone', 'title' => 'Evangelistic Crusade', 'date' => 'Jul 12 - 26', 'desc' => 'Two weeks of powerful preaching and Bible studies.'],
                ['icon' => 'mdi-account-group', 'title' => 'Sabbath Fellowship', 'date' => 'Jul 19, 2026', 'desc' => 'Special inter-church fellowship and potluck gathering.'],
            ];
            @endphp
            @foreach($events as $e)
            <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="ws-event-card">
                    <div class="ws-event-icon"><i class="mdi {{ $e['icon'] }}"></i></div>
                    <div class="ws-event-date"><i class="mdi mdi-calendar-outline me-1"></i>{{ $e['date'] }}</div>
                    <h5 class="ws-event-title">{{ $e['title'] }}</h5>
                    <p class="ws-event-desc">{{ $e['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ANNOUNCEMENTS --}}
<section class="ws-section ws-section-alt">
    <div class="container">
        <div class="text-center mb-5" data-aos="fade-up">
            <span class="ws-section-badge">Stay Updated</span>
            <h2 class="ws-section-title">Latest Announcements</h2>
        </div>
        <div class="row g-4">
            @php
            $announcements = [
                ['title' => 'Upcoming Baptism', 'desc' => 'Rejoice with us as new members commit their lives to Christ this Sabbath through baptism.', 'date' => 'June 22, 2026'],
                ['title' => 'Prayer Meeting Schedule', 'desc' => 'Wednesday evening prayer meetings resume at 6:30 PM. Everyone is welcome.', 'date' => 'June 18, 2026'],
                ['title' => 'District Fellowship', 'desc' => 'Annual inter-district fellowship gathering scheduled for August 3rd.', 'date' => 'June 15, 2026'],
            ];
            @endphp
            @foreach($announcements as $a)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 120 }}">
                <div class="ws-announce-card">
                    <div class="ws-announce-header">
                        <span class="ws-announce-badge"><i class="mdi mdi-bullhorn-outline me-1"></i>Announcement</span>
                        <span class="ws-announce-date"><i class="mdi mdi-clock-outline me-1"></i>{{ $a['date'] }}</span>
                    </div>
                    <h5>{{ $a['title'] }}</h5>
                    <p>{{ $a['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA BANNER --}}
<section class="ws-cta" data-aos="fade-up">
    <div class="container text-center">
        <h2 class="ws-cta-title">You Are Welcome Here</h2>
        <p class="ws-cta-sub">Whether you're seeking God for the first time or looking for a church family, our doors are open.</p>
        <a href="{{ route('website.contact') }}" class="ws-btn ws-btn-primary ws-btn-lg"><i class="mdi mdi-map-marker me-2"></i>Visit Us This Sabbath</a>
    </div>
</section>

@endsection
