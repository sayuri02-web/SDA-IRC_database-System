@extends('website.layout')
@section('title', 'Contact - SDA-IRC Church')
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>Contact Us</h1><p>We'd love to hear from you.</p></div></section>

<section class="ws-section">
    <div class="container">
        <div class="row g-5">
            <div class="col-md-5" data-aos="fade-right">
                <h3 class="mb-4">Get In Touch</h3>
                <div class="ws-contact-list">
                    <a href="https://www.google.com/maps/search/?api=1&query=Santol+St+Dumanlas+Rd+Buhangin+Davao+City+Philippines+8000" target="_blank" rel="noopener noreferrer" class="ws-contact-item">
                        <span class="ws-contact-icon"><i class="mdi mdi-map-marker"></i></span>
                        <span class="ws-contact-text">Santol St., Dumanlas Rd., Buhangin, Davao City, Davao del Sur, Mindanao, Philippines, 8000</span>
                    </a>
                    <a href="tel:+630841234567" class="ws-contact-item">
                        <span class="ws-contact-icon"><i class="mdi mdi-phone"></i></span>
                        <span class="ws-contact-text">(084) 123-4567</span>
                    </a>
                    <a href="mailto:info@sdairc.org" class="ws-contact-item">
                        <span class="ws-contact-icon"><i class="mdi mdi-email"></i></span>
                        <span class="ws-contact-text">info@sdairc.org</span>
                    </a>
                    <a href="https://www.facebook.com/profile.php?id=100095377032072" target="_blank" rel="noopener noreferrer" class="ws-contact-item">
                        <span class="ws-contact-icon"><i class="mdi mdi-facebook"></i></span>
                        <span class="ws-contact-text">SDAIRCPI-Adventist Youth Council</span>
                    </a>
                </div>

                <h5 class="mt-4 mb-3">Service Schedule</h5>
                <p><strong>Sabbath School:</strong> 8:00 AM<br><strong>Divine Service:</strong> 10:00 AM<br><strong>AY Program:</strong> 2:00 PM<br><strong>Sundown Worship:</strong> 5:30 PM</p>
            </div>
            <div class="col-md-7" data-aos="fade-left">
                <div class="ws-map-container">
                    <iframe
                        src="https://maps.google.com/maps?q=Santol+St+Dumanlas+Rd+Buhangin+Davao+City+Philippines+8000&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                    <a href="https://www.google.com/maps/search/?api=1&query=Santol+St+Dumanlas+Rd+Buhangin+Davao+City+Philippines+8000" target="_blank" rel="noopener noreferrer" class="ws-map-overlay-link" title="Open in Google Maps">
                        <i class="mdi mdi-open-in-new"></i> Open in Google Maps
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
