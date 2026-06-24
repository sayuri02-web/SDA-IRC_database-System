<!DOCTYPE html>
<html>
<head>
    <title>Student Certificate - {{ $fullName }}</title>
    @vite('resources/scss/certificates/studentcert.scss')
</head>

<body class="stu-print-body" onload="window.print()">

<div class="stu-page">

    <div class="stu-content">

        {{-- HEADER --}}
        <div class="stu-header">
            <img src="{{ asset('assets/images/logo.jpg') }}" class="stu-logo" alt="Church Logo">
            <div class="stu-header-text">
                <div class="stu-church-name">Seventh-Day Adventist Church</div>
                <div class="stu-church-sub">
                    <span class="stu-sub-blue">Inter-Regional Conference in the Philippines Inc.</span><br>
                    SEC Reg. No. D200000116<br>
                    Santol St., Buhangin, Davao City<br>
                    Cell No. 09169329749/09286677153
                </div>
            </div>
        </div>

        {{-- BLUE LINE --}}
        <div class="stu-header-line"></div>

        {{-- BODY --}}
        <div class="stu-body">

            <p class="stu-salutation"><strong>To Whom It May Concern</strong></p>

            <p class="stu-paragraph">
                This is to certify that <strong><u>{{ $fullName }}</u></strong> is a bona fide member of the Seventh-Day
                Adventist Church Inter-Regional Conference, Inc. local church in {{ $churchLocation }}. Our
                doctrine adheres strictly in the observation of the Seventh-day Sabbath based in the Bible.
            </p>

            <p class="stu-paragraph">
                In view hereof, <strong>{{ $displayName }}</strong> wishes to practice {{ $possessive }} religious freedom which was
                provided in our constitution. The Philippine Constitution, Article III, Section 5; &ldquo;No law shall
                be made respecting the establishment of religion, or prohibiting the free exercise thereof, and
                the free exercise and enjoyment of religious profession shall be forever allowed.&rdquo;
            </p>

            <p class="stu-paragraph">
                Therefore, we pray that {{ $pronoun }} may be excused in any school activities during Saturdays.
            </p>

            <p class="stu-done-line">
                Done this {{ \Carbon\Carbon::parse($issuedDate)->format('jS') }} day of {{ \Carbon\Carbon::parse($issuedDate)->format('F Y') }} in {{ $churchLocation }}, Philippines.
            </p>

        </div>

        {{-- SIGNATURES --}}
        <div class="stu-signature-section">

            <p class="stu-signed-label">Signed:</p>

            <div class="stu-sig-grid">
                @if($elder1)
                <div class="stu-sig-block">
                    <div class="stu-sig-name">{{ $elder1 }}</div>
                    <div class="stu-sig-line"></div>
                    <div class="stu-sig-title">Church Elder</div>
                </div>
                @endif

                @if($elder2)
                <div class="stu-sig-block">
                    <div class="stu-sig-name">{{ $elder2 }}</div>
                    <div class="stu-sig-line"></div>
                    <div class="stu-sig-title">Church Elder</div>
                </div>
                @endif

                @if($elder3)
                <div class="stu-sig-block">
                    <div class="stu-sig-name">{{ $elder3 }}</div>
                    <div class="stu-sig-line"></div>
                    <div class="stu-sig-title">Church Elder</div>
                </div>
                @endif

                @if($elder4)
                <div class="stu-sig-block">
                    <div class="stu-sig-name">{{ $elder4 }}</div>
                    <div class="stu-sig-line"></div>
                    <div class="stu-sig-title">Church Elder</div>
                </div>
                @endif
            </div>

        </div>

    </div>

</div>

</body>
</html>
