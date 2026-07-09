@extends('website.layout')
@section('title', $album->title . ' - Gallery - SDA-IRC Church')
@section('content')

<section class="ws-page-hero" style="background: linear-gradient(135deg, {{ $album->gradient_from }}, {{ $album->gradient_to }});">
    <div class="container" data-aos="fade-up">
        <h1>{{ $album->title }}</h1>
        <p>{{ $album->description ?: 'Browse photos from this album.' }}</p>
    </div>
</section>

<section class="ws-section">
    <div class="container">

        {{-- Back + Info --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <a href="{{ route('website.gallery') }}" class="ws-btn ws-btn-outline" style="border:1.5px solid #e2e8f0; color:#525f7f; padding:10px 20px; border-radius:10px; text-decoration:none; font-size:14px; font-weight:600; transition:0.2s;">
                <i class="mdi mdi-arrow-left me-1"></i> Back to Gallery
            </a>
            <span style="font-size:14px; color:#8898aa; font-weight:500;">
                <i class="mdi mdi-camera-outline me-1"></i> {{ $album->photos_count }} Photos
            </span>
        </div>

        {{-- Photo Grid --}}
        @if($photos->count() > 0)
        <div class="row g-3">
            @foreach($photos as $photo)
            <div class="col-lg-3 col-md-4 col-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                <div class="ws-album-photo" data-lightbox="{{ $loop->index }}" role="button" tabindex="0" aria-label="View photo {{ $loop->iteration }} of {{ $photos->count() }}">
                    <img src="{{ asset('uploads/gallery/' . $photo->filename) }}" alt="{{ $photo->caption ?: $album->title }}" loading="lazy">
                </div>
            </div>
            @endforeach
        </div>

        {{-- Lightbox Viewer --}}
        <div class="ws-lightbox" id="wsLightbox" role="dialog" aria-modal="true" aria-label="Image viewer">
            <div class="ws-lightbox-overlay"></div>
            <button class="ws-lightbox-close" aria-label="Close viewer"><i class="mdi mdi-close"></i></button>
            <div class="ws-lightbox-counter"><span id="lbCurrent">1</span> of <span id="lbTotal">{{ $photos->count() }}</span></div>
            <button class="ws-lightbox-nav ws-lightbox-prev" aria-label="Previous image"><i class="mdi mdi-chevron-left"></i></button>
            <button class="ws-lightbox-nav ws-lightbox-next" aria-label="Next image"><i class="mdi mdi-chevron-right"></i></button>
            <div class="ws-lightbox-stage">
                <img id="lbImage" src="" alt="" draggable="false">
            </div>
            <div class="ws-lightbox-caption" id="lbCaption"></div>
        </div>

        <script>
        (function() {
            var photos = @json($photos->map(fn($p) => ['url' => asset('uploads/gallery/' . $p->filename), 'caption' => $p->caption ?: ''])->values());
            var lb = document.getElementById('wsLightbox');
            var img = document.getElementById('lbImage');
            var caption = document.getElementById('lbCaption');
            var counter = document.getElementById('lbCurrent');
            var currentIndex = 0;
            var scale = 1;
            var translateX = 0, translateY = 0;
            var isDragging = false, startX, startY, startTX, startTY;
            var touchStartX = 0, touchStartY = 0, touchMoved = false;

            function show(index) {
                currentIndex = index;
                scale = 1; translateX = 0; translateY = 0;
                applyTransform();
                img.src = photos[index].url;
                img.alt = photos[index].caption || 'Photo ' + (index + 1);
                caption.textContent = photos[index].caption || '';
                caption.style.display = photos[index].caption ? 'block' : 'none';
                counter.textContent = index + 1;
                lb.classList.add('active');
                document.body.style.overflow = 'hidden';
                // Preload adjacent
                if (photos[index + 1]) { var p = new Image(); p.src = photos[index + 1].url; }
                if (photos[index - 1]) { var p2 = new Image(); p2.src = photos[index - 1].url; }
            }

            function close() {
                lb.classList.remove('active');
                document.body.style.overflow = '';
            }

            function prev() { if (currentIndex > 0) show(currentIndex - 1); }
            function next() { if (currentIndex < photos.length - 1) show(currentIndex + 1); }

            function applyTransform() {
                img.style.transform = 'scale(' + scale + ') translate(' + (translateX / scale) + 'px, ' + (translateY / scale) + 'px)';
            }

            function zoomIn() { scale = Math.min(scale * 1.5, 4); applyTransform(); }
            function zoomOut() { scale = Math.max(scale / 1.5, 1); if (scale === 1) { translateX = 0; translateY = 0; } applyTransform(); }
            function resetZoom() { scale = 1; translateX = 0; translateY = 0; applyTransform(); }

            // Click to open
            document.querySelectorAll('[data-lightbox]').forEach(function(el) {
                el.addEventListener('click', function() { show(parseInt(el.dataset.lightbox)); });
                el.addEventListener('keydown', function(e) { if (e.key === 'Enter') show(parseInt(el.dataset.lightbox)); });
            });

            // Close
            lb.querySelector('.ws-lightbox-close').addEventListener('click', close);
            lb.querySelector('.ws-lightbox-overlay').addEventListener('click', close);

            // Nav
            lb.querySelector('.ws-lightbox-prev').addEventListener('click', function(e) { e.stopPropagation(); prev(); });
            lb.querySelector('.ws-lightbox-next').addEventListener('click', function(e) { e.stopPropagation(); next(); });

            // Keyboard
            document.addEventListener('keydown', function(e) {
                if (!lb.classList.contains('active')) return;
                if (e.key === 'Escape') close();
                else if (e.key === 'ArrowLeft') prev();
                else if (e.key === 'ArrowRight') next();
            });

            // Mouse wheel zoom
            lb.querySelector('.ws-lightbox-stage').addEventListener('wheel', function(e) {
                e.preventDefault();
                if (e.deltaY < 0) zoomIn(); else zoomOut();
            }, { passive: false });

            // Double click zoom
            img.addEventListener('dblclick', function() {
                if (scale > 1) resetZoom(); else { scale = 2.5; applyTransform(); }
            });

            // Drag when zoomed
            img.addEventListener('mousedown', function(e) {
                if (scale <= 1) return;
                isDragging = true;
                startX = e.clientX; startY = e.clientY;
                startTX = translateX; startTY = translateY;
                img.style.cursor = 'grabbing';
                e.preventDefault();
            });
            document.addEventListener('mousemove', function(e) {
                if (!isDragging) return;
                translateX = startTX + (e.clientX - startX);
                translateY = startTY + (e.clientY - startY);
                applyTransform();
            });
            document.addEventListener('mouseup', function() {
                isDragging = false;
                img.style.cursor = '';
            });

            // Touch swipe & zoom
            var stage = lb.querySelector('.ws-lightbox-stage');
            stage.addEventListener('touchstart', function(e) {
                if (e.touches.length === 1 && scale <= 1) {
                    touchStartX = e.touches[0].clientX;
                    touchStartY = e.touches[0].clientY;
                    touchMoved = false;
                }
            }, { passive: true });
            stage.addEventListener('touchmove', function(e) {
                if (e.touches.length === 1 && scale <= 1) { touchMoved = true; }
            }, { passive: true });
            stage.addEventListener('touchend', function(e) {
                if (!touchMoved || scale > 1) return;
                var diff = e.changedTouches[0].clientX - touchStartX;
                if (Math.abs(diff) > 50) { if (diff > 0) prev(); else next(); }
            }, { passive: true });

            // Double tap zoom (mobile)
            var lastTap = 0;
            img.addEventListener('touchend', function(e) {
                var now = Date.now();
                if (now - lastTap < 300) {
                    if (scale > 1) resetZoom(); else { scale = 2.5; applyTransform(); }
                    e.preventDefault();
                }
                lastTap = now;
            });
        })();
        </script>
        @else
        <div class="text-center py-5">
            <i class="mdi mdi-image-outline" style="font-size:56px; color:#d0d5dc;"></i>
            <p class="text-muted mt-3" style="font-size:15px;">No photos have been uploaded to this album yet.</p>
        </div>
        @endif

    </div>
</section>

@endsection
