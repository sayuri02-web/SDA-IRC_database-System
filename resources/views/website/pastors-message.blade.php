@extends('website.layout')
@section('title', "Pastor's Message")
@section('content')

<section class="ws-page-hero"><div class="container" data-aos="fade-up"><h1>Pastor's Message</h1><p>A word of encouragement from our spiritual leader.</p></div></section>

<section class="ws-section">
    <div class="container">
        <div class="row g-5 align-items-start">
            <div class="col-md-4 text-center" data-aos="fade-right">
                @if($pastorMessage && $pastorMessage->member && $pastorMessage->member->photo)
                    <img src="{{ asset('uploads/' . $pastorMessage->member->photo) }}"
                         alt="{{ $pastorMessage->member->full_name }}"
                         style="width:200px; height:200px; border-radius:50%; object-fit:cover; border:4px solid #e8ede9;">
                @else
                    <div class="ws-pastor-photo"><i class="mdi mdi-account-tie"></i></div>
                @endif

                <h4 class="mt-3">
                    {{ $pastorMessage && $pastorMessage->member ? $pastorMessage->member->full_name : 'Pastor Name' }}
                </h4>
                <p class="text-muted">
                    @if($pastorMessage && $pastorMessage->member)
                        {{ $pastorMessage->member->organization ? $pastorMessage->member->organization . ' • ' : '' }}{{ $pastorMessage->member->position ?? 'Pastor' }}
                    @else
                        Senior Pastor, SDA-IRC
                    @endif
                </p>
            </div>
            <div class="col-md-8" data-aos="fade-left">
                @if($pastorMessage && $pastorMessage->title)
                    <h3 class="mb-3">"{{ $pastorMessage->title }}"</h3>
                @else
                    <h3 class="mb-3">"Walking by Faith, Not by Sight"</h3>
                @endif

                @if($pastorMessage && $pastorMessage->content)
                    {!! nl2br(e($pastorMessage->content)) !!}
                @else
                    <p>Dear Brothers and Sisters in Christ,</p>
                    <p>It is with great joy that I welcome you to our church family. In these times of uncertainty, we are called to anchor our faith in God's unchanging Word. As we navigate the challenges of daily life, let us remember that our hope is built on nothing less than Jesus' blood and righteousness.</p>
                    <p>Our church is a place where you can find community, support, and spiritual growth. Whether you are a long-time member or visiting for the first time, know that God has a special plan for your life, and we are here to walk alongside you on this journey of faith.</p>
                    <p>I encourage you to participate in our programs, connect with fellow believers, and grow deeper in your relationship with God. Together, we can make a difference in our community and beyond.</p>
                    <p>May God bless you abundantly.</p>
                @endif

                @if($pastorMessage && $pastorMessage->member)
                    <p class="mt-4"><strong>In His Service,<br>{{ $pastorMessage->member->full_name }}</strong></p>
                @else
                    <p class="mt-4"><strong>In His Service,<br>Pastor</strong></p>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
