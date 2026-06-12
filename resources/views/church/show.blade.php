@extends('layout')
@section('content')

<div class="top-actions">
    <a href="{{ url('/members') }}" class="btn btn-secondary btn-sm">
        ← Back
    </a>

    <button onclick="window.print()" class="btn btn-primary btn-sm">
        Print Docunent
    </button>
</div>

<div class="cv-container">

    <!-- HEADER -->
    <div class="cv-header">

        <div style="display:flex; gap:20px; align-items:center; justify-content:center; flex-wrap:wrap;">
            <!-- PHOTO CONTAINER -->
            <div style="position:relative;">
                @if($members->photo)
                    <img src="{{ asset('uploads/' . $members->photo) }}"
                        style="width:160px;height:160px;object-fit:cover;border-radius:10px;">
                @endif
            </div>

            <!-- INFO -->
            <div style="text-align:left; max-width:500px;">

                <h1 style="margin:0; font-size:28px;">
                    {{ $members->first_name }} {{ $members->middle_initial }} {{ $members->last_name }}
                </h1>

                <p style="margin:5px 0;">
                    📞 {{ $members->mobile }}
                </p>

                <p style="margin:0;">
                    📍 {{ $members->barangay }},
                    {{ $members->city }},
                    {{ $members->region }},
                    {{ $members->postal_code }}
                </p>

            </div>

        </div>

    </div>

    <!-- PERSONAL INFO -->
    <div class="section-title">Personal Information</div>

    <table class="info-table">
        <tr>
            <td class="label">Gender</td>
            <td>{{ $members->gender }}</td>
        </tr>
        <tr>
            <td class="label">Birthdate</td>
            <td>{{ \Carbon\Carbon::parse($members->birthdate)->format('F d, Y') }}</td>
        </tr>
        <tr>
            <td class="label">Age</td>
            <td>{{ \Carbon\Carbon::parse($members->birthdate)->age }} years old</td>
        </tr>
        <tr>
            <td class="label">Birthplace</td>
            <td>{{ $members->birthplace }}</td>
        </tr>
    </table>

    <!-- PARENTS INFO -->
    <div class="section-title">Parents Information</div>

    <table class="info-table">
        <tr>
            <td class="label">Father</td>
            <td>{{ $members->father_name }}</td>
        </tr>
        <tr>
            <td class="label">Mother</td>
            <td>{{ $members->mother_name }}</td>
        </tr>
    </table>

    <!-- ADDRESS -->
    <div class="section-title">Address</div>

    <table class="info-table">
        <tr>
            <td class="label">Full Address</td>
            <td>
                {{ $members->barangay }},
                {{ $members->city }},
                {{ $members->region }},
                {{ $members->postal_code }}
            </td>
        </tr>
    </table>

    <!-- CHURCH INFO -->
    <div class="section-title">Church Information</div>

    <table class="info-table">
        <tr>
            <td class="label">Cluster</td>

            <td>
                {{ $members->church->cluster ?? 'N/A' }}
            </td>
        </tr>
        <tr>
            <td class="label">Baptism Date</td>

            <td>
                @if($members->baptism_date)
                    {{ \Carbon\Carbon::parse($members->baptism_date)->format('F d, Y') }}
                @else
                    N/A
                @endif
            </td>
        </tr>

        <tr>
            <td class="label">Baptism Place</td>

            <td>
                {{ $members->baptism_place ?? 'N/A' }}
            </td>
        </tr>

        <tr>
            <td class="label">Minister</td>

            <td>
                {{ $members->officiating_minister ?? 'N/A' }}
            </td>
        </tr>
    </table>

</div>

@endsection