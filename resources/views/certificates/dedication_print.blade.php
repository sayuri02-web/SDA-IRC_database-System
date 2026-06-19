<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Dedication</title>
    @vite('resources/scss/certificates/dedication_print.scss')
</head>

<body class="ded-print-body" onload="window.print()">

<div class="ded-page">
    <div class="ded-outer-border">
        <div class="ded-inner-border">

            {{-- HEADER --}}
            <div class="ded-header">
                <img src="{{ asset('assets/images/logo.jpg') }}" class="ded-logo">
                <div class="ded-church-name">Seventh-day Adventist Church</div>
                <div class="ded-church-sub">
                    Inter-Regional Conference in the Philippines, Inc.<br>
                    SEC. Reg. No. D2000-00116<br>
                    {{ $request->church_location ?? 'Davao City, Philippines' }}
                </div>
            </div>

            {{-- TITLE --}}
            <div class="ded-title">Certificate of Dedication</div>

            {{-- BODY --}}
            <div class="ded-body">
                <div class="ded-intro">This is to Certify</div>

                {{-- NAME --}}
                <div class="ded-name-line">
                    {{ trim($member->first_name . ' ' . ($member->middle_initial ? $member->middle_initial . ' ' : '') . $member->last_name . ' ' . ($member->suffix ?? '')) }}
                </div>
                <div class="ded-name-label">Name</div>

                {{-- BIRTH --}}
                <div class="ded-birth-row">
                    <span>Born at</span>
                    <span class="ded-line ded-birth-place">{{ $member->birthplace }}</span>
                    <span>on</span>
                    <span class="ded-line ded-birth-date">{{ \Carbon\Carbon::parse($member->birthdate)->format('F d, Y') }}</span>
                </div>

                {{-- PARENTS --}}
                <div class="ded-parents">
                    <div class="ded-parent-box">
                        <div class="ded-parent-line">{{ $member->father_name }}</div>
                        <div class="ded-parent-label">Father</div>
                    </div>
                    <div class="ded-parent-and">and</div>
                    <div class="ded-parent-box">
                        <div class="ded-parent-line">{{ $member->mother_name }}</div>
                        <div class="ded-parent-label">Mother</div>
                    </div>
                </div>

                {{-- DEDICATION STATEMENT --}}
                <div class="ded-statement">
                    Who acknowledge that God gave them this precious gift and that they
                    are responsible in guiding this child for the glory of God.<br>
                    This solemn act of dedication was held at
                </div>

                <div class="ded-church-italic">Seventh-day Adventist Church</div>

                {{-- LOCATION & DATE --}}
                <div class="ded-location-row">
                    <span>Of</span>
                    <span class="ded-line ded-location-value">{{ $request->church_location ?? '' }}</span>
                </div>

                <div class="ded-date-row">
                    <span>On</span>
                    <span class="ded-line ded-date-day">{{ $request->dedication_date ? \Carbon\Carbon::parse($request->dedication_date)->format('d') : '' }}</span>
                    <span>day of</span>
                    <span class="ded-line ded-date-month">{{ $request->dedication_date ? \Carbon\Carbon::parse($request->dedication_date)->format('F') : '' }}</span>
                    <span>,</span>
                    <span class="ded-line ded-date-year">{{ $request->dedication_date ? \Carbon\Carbon::parse($request->dedication_date)->format('Y') : '' }}</span>
                </div>

                {{-- SIGNATURES --}}
                <div class="ded-signatures">
                    <div class="ded-sig-left">
                        <div class="ded-sig-line">{{ $request->chairman ?? '' }}</div>
                        <div class="ded-sig-label">Chairman of Organization</div>
                    </div>
                    <div class="ded-sig-right">
                        <div class="ded-sig-line">{{ $request->officiating_minister ?? $member->officiating_minister ?? '' }}</div>
                        <div class="ded-sig-label">Officiating Minister</div>
                    </div>
                </div>

                {{-- WITNESSES --}}
                <div class="ded-witnesses">

                    @foreach($witnesses as $witness)
                        <div class="ded-witness-line">
                            {{ $witness }}
                        </div>
                    @endforeach

                    <div class="ded-witness-label">
                        Witnesses
                    </div>

                </div>

            </div>

            {{-- BIBLE VERSE FOOTER --}}
            <div class="ded-footer-verse">
                "I asked Him for this child, and He gave me what I asked for. So I am dedicating him to the Lord. As long as he lives, he will be belong to the Lord." (1 Samuel 1:27-28 GNB).
            </div>

        </div>
    </div>
</div>

</body>
</html>
