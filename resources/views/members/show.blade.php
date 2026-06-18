@extends('layout')
@section('content')

{{-- BREADCRUMB + ACTIONS --}}
<div class="member-show-top">
    <div class="church-breadcrumb">
        @if(request('from') === 'leaders')
        <a href="{{ url('/leaders-directory') }}" class="church-breadcrumb-link">
            <i class="mdi mdi-account-tie me-1"></i>Leaders Directory
        </a>
        @else
        <a href="{{ url('/members') }}" class="church-breadcrumb-link">
            <i class="mdi mdi-account-group me-1"></i>Members
        </a>
        @endif
        <i class="mdi mdi-chevron-right mx-1 church-breadcrumb-sep"></i>
        <span class="church-breadcrumb-current">View Member</span>
    </div>
    <div class="member-show-btns">
        <a href="{{ request('from') === 'leaders' ? url('/leaders-directory') : url('/members') }}" class="btn btn-outline-secondary btn-sm">
            <i class="mdi mdi-arrow-left me-1"></i> Back
        </a>
        <button onclick="window.print()" class="btn btn-outline-success btn-sm">
            <i class="mdi mdi-printer-outline me-1"></i> Print
        </button>
    </div>
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

                @if(request('from') === 'leaders' && $members->is_leader)
                <p style="margin:4px 0 8px; display:flex; align-items:center; gap:8px;">
                    <span style="display:inline-block; padding:3px 10px; border-radius:6px; background:#eef4ff; color:#2449d8; font-size:14px; font-weight:600;">{{ $members->organization }} :</span>
                    <span style="font-size:14px; color:#525f7f; font-weight:500;">{{ $members->position }}</span>
                </p>
                @endif

                <p style="margin:8px 0; display:flex; align-items:center; gap:10px;">
                    <i class="mdi mdi-cellphone" style="color:#28a745; font-size:20px;"></i>
                    <span style="font-size:15px; color:#333;">{{ $members->mobile }}</span>
                </p>

                <p style="margin:0; display:flex; align-items:center; gap:10px;">
                    <i class="mdi mdi-map-marker" style="color:#2449d8; font-size:20px;"></i>
                    <span style="font-size:15px; color:#333;">
                        {{ $members->barangay }},
                        {{ $members->city }},
                        {{ $members->region }},
                        {{ $members->postal_code }}
                    </span>
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
                {{ $members->street }},
                {{ $members->barangay }},
                {{ $members->city }},
                {{ $members->region }},
            </td>
        </tr>
    </table>

    <!-- CHURCH INFO -->
    <div class="section-title">Church Information</div>

    <table class="info-table">
        <tr>
            <td class="label">Cluster</td>
            <td>{{ $members->church->cluster ?? 'N/A' }}</td>
        </tr>
        
        <tr>
            <td class="label">
                {{ $members->membership_status === 'dedicated' ? 'Dedication Date' : 'Baptism Date' }}
            </td>

            <td>
                {{ $members->baptism_date ? \Carbon\Carbon::parse($members->baptism_date)->format('F d, Y') : 'N/A' }}
            </td>
        </tr>

        <tr>
            <td class="label">
                {{ $members->membership_status === 'dedicated' ? 'Dedication Place' : 'Baptism Place' }}
            </td>

            <td>
                {{ $members->baptism_place ?? 'N/A' }}
            </td>
        </tr>

        <tr>
            <td class="label">
                {{ $members->membership_status === 'dedicated' ? 'Dedication Minister' : 'Baptism Minister' }}
            </td>

            <td>
                {{ $members->officiating_minister ?? 'N/A' }}
            </td>
        </tr>

    </table>

</div>

@endsection
