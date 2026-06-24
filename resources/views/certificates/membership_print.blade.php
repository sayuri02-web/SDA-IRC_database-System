<!DOCTYPE html>
<html>
<head>
    <title>Church Certification - {{ $fullName }}</title>
    @vite('resources/scss/certificates/membership_print.scss')
</head>

<body class="mem-print-body" onload="window.print()">

<div class="mem-page">

    <div class="mem-content">

        {{-- HEADER --}}
        <div class="mem-header">
            <img src="{{ asset('assets/images/logo.jpg') }}" class="mem-logo" alt="Church Logo">
            <div class="mem-header-text">
                <div class="mem-church-name">Seventh-Day Adventist Church</div>
                <div class="mem-church-sub">
                    <span class="mem-sub-red">Inter-Regional Conference in the Philippines Inc.</span><br>
                    SEC Reg. No. D2000-00116<br>
                    Santol St., Sto. Niño, Buhangin, Davao City <br>
                </div>
            </div>
        </div>

        {{-- TITLE --}}
        <div class="mem-title">Church Certification</div>

        {{-- BODY --}}
        <div class="mem-body">

            <p class="mem-salutation">TO WHOM IT MAY CONCERN:</p>

            <p class="mem-paragraph">
                This is to certify that <span class="mem-name-bold">{{ $fullName }}</span> is a regular and active member of the
                Seventh-day Adventist Church Inter-Regional Conference in the Philippines, Inc. local
                church in {{ $churchLocation }}.
            </p>

            @if($position)
            <p class="mem-paragraph">
                To certify further that <span class="mem-name-bold">{{ $fullName }}</span> is one of our elected {{ $position }}.
            </p>
            @endif

            <p class="mem-paragraph">
                This certificate is issued on the {{ \Carbon\Carbon::parse($issuedDate)->format('jS') }} day of {{ \Carbon\Carbon::parse($issuedDate)->format('F Y') }}, for whatever purpose it may serve.
            </p>

        </div>

        {{-- SECRETARY SIGNATURE --}}
        <div class="mem-signature-section">
            @if($secretaryName)
            <div class="mem-sig-block">
                <div class="mem-sig-name">{{ $secretaryName }}</div>
                <div class="mem-sig-line"></div>
                <div class="mem-sig-title">Secretary, SDA-IRCPI</div>
            </div>
            @endif
        </div>

        {{-- ELDER SIGNATURE --}}
        <div class="mem-noted-section">
            <p class="mem-noted-label">Noted by:</p>
            @if($elderName)
            <div class="mem-sig-block">
                <div class="mem-sig-name">{{ $elderName }}</div>
                <div class="mem-sig-line"></div>
                <div class="mem-sig-title">Church Elder</div>
            </div>
            @endif
        </div>

    </div>

</div>

</body>
</html>
