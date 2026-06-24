<!DOCTYPE html>
<html>
<head>
    <title>Counseling Certificate - {{ $fullName }}</title>
    @vite('resources/scss/certificates/counseling_print.scss')
</head>

<body class="cou-print-body" onload="window.print()">

<div class="cou-page">

    <div class="cou-content">

        {{-- HEADER --}}
        <div class="cou-header">
            <img src="{{ asset('assets/images/logo.jpg') }}" class="cou-logo" alt="Church Logo">
            <div class="cou-header-text">
                <div class="cou-church-name">Seventh-Day Adventist Church</div>
                <div class="cou-church-sub">
                    <span class="cou-sub-red">Inter-Regional Conference in the Philippines Inc.</span><br>
                    SEC Reg. No. D2000-00116<br>
                    Cell No.: 09752808031<br>
                    Santol St., Sto. Niño, Buhangin, Davao City <br>
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="cou-body">

            <p class="cou-salutation">To Whom It May Concern,</p>

            <p class="cou-paragraph">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that the counseling is done to
                @if($partnerName)
                    them ({{ $fullName }} and {{ $partnerName }})
                @else
                    {{ $fullName }}
                @endif
                {{ $purpose }}
            </p>

        </div>

        {{-- SIGNATURE --}}
        <div class="cou-signature-section">
            @if($chairmanName)
            <div class="cou-sig-block">
                <div class="cou-sig-name">{{ $chairmanName }}</div>
                <div class="cou-sig-title">Chairman of Organization</div>
            </div>
            @endif
        </div>

    </div>

</div>

</body>
</html>
