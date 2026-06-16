<!DOCTYPE html>
<html>
<head>
    
    <title>Certificate of Baptism</title>

    @vite('resources/scss/certificates/baptism_print.scss')

</head>

<body class="baptism-print-body" onload="window.print()">

<div class="baptism-certificate-page">

    <div class="baptism-outer-border">

        <div class="baptism-inner-border">

            <!-- HEADER -->

            <div class="baptism-header">

                <img src="{{ asset('assets/images/logo.jpg') }}"
                     class="baptism-logo">

                <div class="baptism-church-name">
                    Seventh-day Adventist Church
                </div>

                <div class="baptism-church-sub">
                    Inter-Regional Conference in the Philippines, Inc.<br>
                    SEC. Reg. No. D2000-00116<br>
                    Santol St., Buhangin, Davao City, Philippines
                </div>

                <div class="baptism-cert-title">
                    Certificate of Baptism
                </div>

                <div class="baptism-intro">
                    This is to Certify<br>
                    that in obedience to the command and in<br>
                    imitation of the example of our Lord Jesus Christ
                </div>

            </div>

            <!-- NAME -->

            <div class="baptism-name-line">

                {{
                    trim(
                        $member->first_name . ' ' .
                        ($member->middle_initial
                            ? $member->middle_initial . ' '
                            : '') .
                        $member->last_name . ' ' .
                        ($member->suffix ?? '')
                    )
                }}

            </div>

            <div class="baptism-name-label">
                Name
            </div>

            <!-- CONTENT -->

            <div class="baptism-content">

                <div class="baptism-content-row">

                        <span>
                            Born at
                        </span>

                        <span class="baptism-line baptism-birthplace-line">
                            {{ $member->birthplace }}
                        </span>

                        <span>
                            on
                        </span>

                        <span class="baptism-line baptism-date-line">
                            {{ \Carbon\Carbon::parse($member->birthdate)->format('F d, Y') }}
                        </span>

                    </div>

                    <div style="baptism-text">

                        Was buried with Him baptism and was raised like Him to live

                        <br>

                        a new life on the

                        <span class="baptism-line" style="min-width:80px;">
                            {{ $request->baptism_day }}
                        </span>

                        day of

                        <span class="baptism-line" style="min-width:180px;">
                            {{ $request->baptism_month }}
                        </span>,

                        <span class="baptism-line" style="min-width:120px;">
                            {{ $request->baptism_year }}
                        </span>

                        <br>

                        at

                        <span class="baptism-line baptism-place-line">
                            {{ $request->baptism_place }}
                        </span>,

                        Philippines.

                    </div>

                </div>

            <!-- PARENTS -->

            <div class="baptism-parents">

                <div class="baptism-parent-box">

                    <div class="baptism-parent-line">
                        {{ $member->father_name }}
                    </div>

                    <div class="baptism-parent-label">
                        FATHER
                    </div>

                </div>

                <div class="baptism-and">
                    and
                </div>

                <div class="baptism-parent-box">

                    <div class="baptism-parent-line">
                        {{ $member->mother_name }}
                    </div>

                    <div class="baptism-parent-label">
                        MOTHER
                    </div>

                </div>

            </div>

            <!-- SIGNATURES -->

            <div class="baptism-signatures">

                <div class="baptism-sig-box">

                    <div class="baptism-sig-line">
                        {{ $request->chairman }}
                    </div>

                    <div class="baptism-sig-label">
                        Chairman of Organization
                    </div>

                </div>

                <div class="baptism-baptism-and">
                    and
                </div>

                <div class="baptism-sig-box">

                    <div class="baptism-sig-line">
                        {{ $request->officiating_minister }}
                    </div>

                    <div class="baptism-sig-label">
                        Officiating Minister
                    </div>

                </div>

            </div>

            <!-- LOWER -->

            <div class="baptism-bottom-area">

                <div class="baptism-doc-section">

                    Doc. No.__________<br>
                    Page No._________<br>
                    Book No._________<br>
                    Series of _________

                </div>

                <div class="baptism-fellowship">

                    <div class="baptism-fellowship-title">
                        RECEIVED INTO CHURCH FELLOWSHIP
                    </div>

                    Seventh-day Adventist Church<br>
                    Inter-Regional Conference in the Philippines, Inc

                    <br>

                    Of ___________________________

                    <br>

                    on the _____ day of _________________, ______

                    <div class="baptism-secretary">

                        <div class="baptism-secretary-line">
                            {{ $request->secretary }}
                        </div>

                        <div class="baptism-secretary-label">
                            Church Secretary
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script>
window.onload = function(){
    window.print();
};
</script>

</body>
</html>