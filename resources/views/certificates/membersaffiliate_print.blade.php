<!DOCTYPE html>
<html>
<head>
    <title>Members Affiliate Certificate - {{ $fullName }}</title>
    @vite('resources/scss/certificates/membersaffiliate.scss')
</head>

<body class="aff-print-body" onload="window.print()">

<div class="aff-page">

    {{-- LOGO WATERMARK OVERLAY --}}
    <div class="aff-watermark">
        <img src="{{ asset('assets/images/logo.jpg') }}" alt="" style="-webkit-print-color-adjust: exact; print-color-adjust: exact; color-adjust: exact;">
    </div>

    <div class="aff-content">

        {{-- HEADER --}}
        <div class="aff-header">
            <img src="{{ asset('assets/images/logo.jpg') }}" class="aff-logo" alt="Church Logo">
            <div class="aff-header-text">
                <div class="aff-church-name">Seventh-Day Adventist Church</div>
                <div class="aff-church-sub">
                    <span class="aff-sub-red">Inter-Regional Conference in the Philippines Inc.</span><br>
                    SEC Reg. No. D2000-00116<br>
                    Santol St., Sto. Niño, Buhangin, Davao City
                </div>
            </div>
        </div>

        {{-- TITLE --}}
        <div class="aff-title"><em>Membership Affiliation</em></div>

        {{-- BODY --}}
        <div class="aff-body">

            {{-- LINE 1: I, _________, hereby declare... --}}
            <p class="aff-line">
                I, <span class="aff-field aff-field-name">{{ $fullName }}</span>, hereby declare my affiliation as member of the
                <strong>Seventh-day Adventist Church Inter-Regional Conference in the Philippines Inc.</strong> and hereby
                bind myself to obey the constitution and By-Laws/Rules and Regulations of the said Religious
                Organization, and lawful orders of the officers.
            </p>

            {{-- LINE 2: Done this __ day of _______, ____, at the... --}}
            <p class="aff-line">
                Done this <span class="aff-field aff-field-day">{{ \Carbon\Carbon::parse($doneDate)->format('j') }}</span> day of
                <span class="aff-field aff-field-month">{{ \Carbon\Carbon::parse($doneDate)->format('F') }}</span>,
                <span class="aff-field aff-field-year">{{ \Carbon\Carbon::parse($doneDate)->format('Y') }}</span>, at the Seventh-day Adventist Church in
            </p>

            {{-- LINE 3: _______ with Residence/Certificate No._______, issued at _______ --}}
            <p class="aff-line">
                <span class="aff-field aff-field-location">{{ $churchLocation }}</span>with Residence/Certificate No.<span class="aff-field aff-field-certno">{{ $residenceCertNo }}</span>, issued at
                <span class="aff-field aff-field-issuedat">{{ $residenceIssuedAt }}</span>
            </p>

            {{-- LINE 4: _______, Philippines this __ day of _______, ____. --}}
            <p class="aff-line">
                <span class="aff-field aff-field-location">{{ $residenceIssuedAt }}</span>, Philippines this
                <span class="aff-field aff-field-day">{{ $residenceIssuedDate ? \Carbon\Carbon::parse($residenceIssuedDate)->format('j') : '' }}</span> day of
                <span class="aff-field aff-field-month">{{ $residenceIssuedDate ? \Carbon\Carbon::parse($residenceIssuedDate)->format('F') : '' }}</span>,
                <span class="aff-field aff-field-year">{{ $residenceIssuedDate ? \Carbon\Carbon::parse($residenceIssuedDate)->format('Y') : '' }}</span>.
            </p>

        </div>

        {{-- APPLICANT SIGNATURE --}}
        <div class="aff-applicant-sig">
            <div class="aff-sig-line-long"></div>
            <div class="aff-sig-label-right">Signature of Applicant</div>
        </div>

        {{-- RECOMMENDED & NOTED --}}
        <div class="aff-dual-sig">
            <div class="aff-sig-col">
                <p class="aff-sig-prefix">Recommended by:</p>
                <div class="aff-sig-block">
                    <div class="aff-sig-name">{{ $elderName }}</div>
                    <div class="aff-sig-line-med"></div>
                    <div class="aff-sig-title">Church Elder</div>
                </div>
            </div>

            <div class="aff-sig-col">
                <p class="aff-sig-prefix">Noted by:</p>
                <div class="aff-sig-block">
                    <div class="aff-sig-name">{{ $secretaryName }}</div>
                    <div class="aff-sig-line-med"></div>
                    <div class="aff-sig-title">Secretary</div>
                </div>
            </div>
        </div>

        {{-- APPROVED --}}
        <div class="aff-approved-sig">
            <p class="aff-sig-prefix">Approved by:</p>
            <div class="aff-sig-block">
                <div class="aff-sig-name">{{ $chairmanName }}</div>
                <div class="aff-sig-line-med"></div>
                <div class="aff-sig-title">Chairman</div>
            </div>
        </div>

    </div>

</div>

</body>
</html>
