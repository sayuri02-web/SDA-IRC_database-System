@extends('website.layout')
@section('title', 'About Us - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>About Us</h1><p>Learn about our history, values, and leadership.</p></div></section>

<section class="ws-section">
    <div class="container">
        {{-- Church History + Image --}}
        <div class="row g-5 align-items-center mb-5">
            <div class="col-md-6" data-aos="fade-right">
                <h2 class="ws-section-title text-start">Church History</h2>
                @if($churchInfo?->church_history)
                    {!! nl2br(e($churchInfo->church_history)) !!}
                @else
                    <p class="text-muted">Church history will be published soon.</p>
                @endif
            </div>
            <div class="col-md-6" data-aos="fade-left">
                @if($churchInfo?->church_image)
                    <img src="{{ asset('uploads/about/' . $churchInfo->church_image) }}" alt="Church" style="width:100%; height:300px; border-radius:16px; object-fit:cover; display:block;">
                @else
                    <div class="ws-about-img-placeholder"><i class="mdi mdi-home"></i></div>
                @endif
            </div>
        </div>

        {{-- Mission & Vision --}}
        @if($churchInfo?->mission || $churchInfo?->vision)
        <div class="row g-4 mb-5">
            @if($churchInfo?->mission)
            <div class="col-md-6" data-aos="fade-right">
                <div class="ws-mv-card ws-mv-mission">
                    <div class="ws-mv-icon"><i class="mdi mdi-target"></i></div>
                    <h3>Our Mission</h3>
                    <p>{{ $churchInfo->mission }}</p>
                    <div class="ws-mv-accent"></div>
                </div>
            </div>
            @endif
            @if($churchInfo?->vision)
            <div class="col-md-6" data-aos="fade-left">
                <div class="ws-mv-card ws-mv-vision">
                    <div class="ws-mv-icon"><i class="mdi mdi-eye-outline"></i></div>
                    <h3>Our Vision</h3>
                    <p>{{ $churchInfo->vision }}</p>
                    <div class="ws-mv-accent"></div>
                </div>
            </div>
            @endif
        </div>
        @endif

        {{-- Core Values --}}
        @if($churchInfo?->core_values)
        <div class="text-center mb-4" data-aos="fade-up">
            <span class="ws-section-badge">What We Stand For</span>
            <h2 class="ws-section-title">Core Values</h2>
        </div>
        <div class="row g-4 mb-5">
            @foreach(array_filter(explode("\n", $churchInfo->core_values)) as $value)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="ws-value-card">
                    <i class="mdi mdi-check-decagram"></i>
                    <h5>{{ trim($value) }}</h5>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Meet Our Leaders --}}
        @if($leaders->count() > 0)
        <div class="text-center mb-4" data-aos="fade-up">
            <span class="ws-section-badge">Leadership</span>
            <h2 class="ws-section-title">Meet Our Leaders</h2>
        </div>

        {{-- Desktop/Tablet Grid (unchanged) --}}
        <div class="ws-leaders-grid row g-4">
            @foreach($leaders as $leader)
            <div class="col-md-3 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="ws-leader-card">
                    <div class="ws-leader-avatar">
                        @if($leader->member?->photo)
                            <img src="{{ asset('uploads/' . $leader->member->photo) }}" alt="{{ $leader->member->full_name }}" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                        @else
                            <i class="mdi mdi-account"></i>
                        @endif
                    </div>
                    <h6>{{ $leader->member?->full_name ?? '—' }}</h6>
                    @if($leader->member?->organization)
                        <p>{{ $leader->member->organization }}</p>
                    @endif
                    @if($leader->member?->position)
                        <p>{{ $leader->member->position }}</p>
                    @endif
                    @if($leader->member?->church)
                        <p>{{ $leader->member->church->church_name ?? '' }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Mobile Carousel --}}
        <div class="ws-leaders-carousel" data-aos="fade-up">
            <div class="ws-leaders-carousel-track" id="leadersCarouselTrack">
                @foreach($leaders as $leader)
                <div class="ws-leaders-carousel-slide">
                    <div class="ws-leader-card-mobile">
                        <div class="ws-leader-card-mobile-glow"></div>
                        <div class="ws-leader-card-mobile-inner">
                            <div class="ws-leader-avatar-mobile">
                                @if($leader->member?->photo)
                                    <img src="{{ asset('uploads/' . $leader->member->photo) }}" alt="{{ $leader->member->full_name }}">
                                @else
                                    <div class="ws-leader-avatar-mobile-placeholder">
                                        <i class="mdi mdi-account"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="ws-leader-info-mobile">
                                <h6>{{ $leader->member?->full_name ?? '—' }}</h6>
                                @if($leader->member?->position)
                                    <span class="ws-leader-position-mobile">{{ $leader->member->position }}</span>
                                @endif
                                @if($leader->member?->organization)
                                    <span class="ws-leader-org-mobile">{{ $leader->member->organization }}</span>
                                @endif
                                @if($leader->member?->church)
                                    <span class="ws-leader-church-mobile">{{ $leader->member->church->church_name ?? '' }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="ws-leaders-carousel-dots" id="leadersCarouselDots">
                @foreach($leaders as $leader)
                <button class="ws-leaders-dot {{ $loop->first ? 'active' : '' }}" data-index="{{ $loop->index }}" aria-label="Go to leader {{ $loop->iteration }}"></button>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<script>
(function() {
    var track = document.getElementById('leadersCarouselTrack');
    if (!track) return;

    var slides = track.querySelectorAll('.ws-leaders-carousel-slide');
    var dotsContainer = document.getElementById('leadersCarouselDots');
    var dots = dotsContainer ? dotsContainer.querySelectorAll('.ws-leaders-dot') : [];
    var total = slides.length;
    if (total === 0) return;

    var current = 0;
    var autoInterval = null;
    var autoDelay = 2000;
    var touchStartX = 0;
    var touchStartY = 0;
    var touchDiffX = 0;
    var isTouching = false;
    var hasMoved = false;

    function goTo(index, instant) {
        if (index < 0) index = total - 1;
        if (index >= total) index = 0;
        current = index;
        var offset = -(current * 100);
        if (instant) {
            track.style.transition = 'none';
        } else {
            track.style.transition = 'transform 0.45s cubic-bezier(0.25, 0.1, 0.25, 1)';
        }
        track.style.transform = 'translate3d(' + offset + '%, 0, 0)';
        updateDots();
    }

    function updateDots() {
        for (var i = 0; i < dots.length; i++) {
            dots[i].classList.toggle('active', i === current);
        }
    }

    function next() { goTo(current + 1); }

    function startAuto() {
        stopAuto();
        autoInterval = setInterval(next, autoDelay);
    }

    function stopAuto() {
        if (autoInterval) { clearInterval(autoInterval); autoInterval = null; }
    }

    // Dot clicks
    for (var d = 0; d < dots.length; d++) {
        (function(i) {
            dots[i].addEventListener('click', function() { goTo(i); startAuto(); });
        })(d);
    }

    // Touch events
    track.addEventListener('touchstart', function(e) {
        isTouching = true;
        hasMoved = false;
        touchStartX = e.touches[0].clientX;
        touchStartY = e.touches[0].clientY;
        touchDiffX = 0;
        stopAuto();
        track.style.transition = 'none';
    }, { passive: true });

    track.addEventListener('touchmove', function(e) {
        if (!isTouching) return;
        var dx = e.touches[0].clientX - touchStartX;
        var dy = e.touches[0].clientY - touchStartY;
        if (!hasMoved && Math.abs(dy) > Math.abs(dx)) {
            isTouching = false;
            return;
        }
        hasMoved = true;
        touchDiffX = dx;
        var containerWidth = track.parentElement.offsetWidth;
        var pct = (touchDiffX / containerWidth) * 100;
        var offset = -(current * 100) + pct;
        track.style.transform = 'translate3d(' + offset + '%, 0, 0)';
    }, { passive: true });

    track.addEventListener('touchend', function() {
        if (!isTouching && !hasMoved) { startAuto(); return; }
        isTouching = false;
        var containerWidth = track.parentElement.offsetWidth;
        var threshold = containerWidth * 0.2;
        if (touchDiffX < -threshold) { goTo(current + 1); }
        else if (touchDiffX > threshold) { goTo(current - 1); }
        else { goTo(current); }
        startAuto();
    }, { passive: true });

    // Visibility API — pause when tab is hidden
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) { stopAuto(); } else { startAuto(); }
    });

    // Only auto-play on mobile
    function checkViewport() {
        if (window.innerWidth <= 767) { startAuto(); } else { stopAuto(); }
    }

    checkViewport();
    window.addEventListener('resize', checkViewport);
})();
</script>

@endsection
