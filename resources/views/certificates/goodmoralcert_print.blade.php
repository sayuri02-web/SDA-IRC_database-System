<!DOCTYPE html>
<html>
<head>
    <title>Good Moral Certificate - {{ $fullName }}</title>
    @vite('resources/scss/certificates/goodmoralcert.scss')
</head>

<body class="gm-print-body" onload="window.print()">

<div class="gm-page">

    <div class="gm-content">

        {{-- HEADER --}}
        <div class="gm-header">
            <img src="{{ asset('assets/images/logo.jpg') }}" class="gm-logo" alt="Church Logo">
            <div class="gm-header-text">
                <div class="gm-church-name">Seventh-Day Adventist Church</div>
                <div class="gm-church-sub">
                    <span class="gm-sub-red">Inter-Regional Conference in the Philippines Inc.</span><br>
                    SEC Reg. No. D2000-00116<br>
                    Santol St., Sto. Niño, {{ $churchLocation }}
                </div>
            </div>
        </div>

        {{-- TITLE --}}
        <div class="gm-title">Certificate of Good Moral</div>

        {{-- BODY --}}
        <div class="gm-body">

            <p class="gm-salutation">TO WHOM IT MAY CONCERN:</p>

            <p class="gm-paragraph">
                This is to certify that <strong>{{ $fullName }}</strong> is a bonafide
                member of the Seventh-day Adventist Church Inter-Regional Conference in the
                Philippines, Inc. local church in {{ $churchLocation }}.
            </p>

            <p class="gm-paragraph">
                This is to further certify that {{ $pronoun }} is a member of good character and has never been
                the subject of any disciplinary action.
            </p>

            <p class="gm-paragraph">
                This certification is issued {{ \Carbon\Carbon::parse($issuedDate)->format('jS') }} day of {{ \Carbon\Carbon::parse($issuedDate)->format('F Y') }} upon {{ $shortName }}'s request
                @if($purpose)
                for {{ $purpose }}.
                @else
                for whatever purpose it may serve.
                @endif
            </p>

        </div>

        {{-- SIGNATURE --}}
        <div class="gm-signature-section">
            @if($elderName)
            <div class="gm-sig-block">
                <div class="gm-sig-name">{{ $elderName }}</div>
                <div class="gm-sig-line"></div>
                <div class="gm-sig-title">&nbsp;&nbsp;&nbsp;Church Elder</div>
            </div>
            @endif
        </div>

    </div>

</div>

</body>
</html>
