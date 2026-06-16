@extends('layout')
@section('content')

<div class="col-12 edit-page-wrapper">

    {{-- TRANSACTION HEADER (fixed, never scrolls) --}}
    <div class="church-breadcrumb mb-3">
        <a href="{{ url('/members') }}" class="church-breadcrumb-link">
            <i class="mdi mdi-account-group me-1"></i>Members
        </a>
        <i class="mdi mdi-chevron-right mx-1 church-breadcrumb-sep"></i>
        <span class="church-breadcrumb-current">Edit Member</span>
    </div>

    <div class="card edit-member-card">
        <div class="church-form-header" style="background: linear-gradient(135deg,#2449d8,#5c7cfa);">
            <div class="church-form-header-icon" style="background: rgba(255,255,255,0.2);">
                <i class="mdi mdi-account-edit"></i>
            </div>
            <div>
                <h4 class="church-form-title mb-0">Edit Member</h4>
                <p class="church-form-subtitle mb-0">Update information for {{ $members->first_name }} {{ $members->last_name }}</p>
            </div>
        </div>

        <div class="card-body edit-member-body">

            <form action="{{ url('members/' . $members->id) }}" method="post" enctype="multipart/form-data" class="form-sample">
                {!! csrf_field() !!}
                @method("PATCH")

                <div class="edit-form-scroll">

                    <h5 class="card-description"> Personal Info </h5>

                    <!-- PHOTO -->
                    <div class="row mb-1">
                        <div class="col-md-6">
                            <div class="row mb-1">
                                <label class="col-sm-2 col-form-label">Photo</label>

                                <div class="col-sm-10">

                                    <input type="file" name="photo" id="photoInput" accept="image/*" hidden>

                                    <div class="input-group">

                                        <input type="text"
                                            class="form-control file-upload-info"
                                            id="fileName"
                                            disabled
                                            placeholder="Upload Image">

                                        <button class="btn btn-outline-success"
                                                type="button"
                                                id="browseBtn">

                                            Upload

                                        </button>

                                    </div>

                                    <div style="margin-top:10px;">
                                        @if($members->photo)
                                            <img src="{{ asset('uploads/' . $members->photo) }}" id="preview" style="max-width:100%; max-height:120px;">
                                        @else
                                            <img id="preview" style="display:none; max-width:100%;">
                                        @endif
                                    </div>

                                    <input type="hidden" name="cropped_photo" id="cropped_photo">

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- NAME -->
                    <div class="row mb-1">

                        <!-- FIRST NAME -->
                        <div class="col-md-6">

                            <div class="row mb-1">

                                <label class="col-sm-2 col-form-label">
                                    First Name
                                </label>

                                <div class="col-sm-10">

                                    <input type="text"
                                        name="first_name"
                                        value="{{ $members->first_name }}"
                                        class="form-control"
                                        placeholder="Firstname">

                                </div>

                            </div>

                        </div>

                        <!-- LAST NAME -->
                        <div class="col-md-6">

                            <div class="row mb-1">

                                <label class="col-sm-2 col-form-label">
                                    Last Name
                                </label>

                                <div class="col-sm-10">

                                    <input type="text"
                                        name="last_name"
                                        value="{{ $members->last_name }}"
                                        class="form-control"
                                        placeholder="Lastname">

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- MIDDLE INITIAL + SUFFIX -->
                    <div class="row mb-1">

                        <div class="col-md-6">

                            <div class=" row mb-1">

                                <label class="col-sm-2 col-form-label">
                                    M.I.
                                </label>

                                <div class="col-sm-3">

                                    <input type="text"
                                        name="middle_initial"
                                        value="{{ $members->middle_initial }}"
                                        class="form-control"
                                        maxlength="1"
                                        placeholder="M.I.">

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="row mb-1">

                                <label class="col-sm-2 col-form-label">
                                    Suffix
                                </label>

                                <div class="col-sm-3">

                                    <select name="suffix" class="form-control">

                                        <option value="">None</option>

                                        <option value="Jr"
                                            {{ $members->suffix == 'Jr' ? 'selected' : '' }}>
                                            Jr
                                        </option>

                                        <option value="Sr"
                                            {{ $members->suffix == 'Sr' ? 'selected' : '' }}>
                                            Sr
                                        </option>

                                        <option value="II"
                                            {{ $members->suffix == 'II' ? 'selected' : '' }}>
                                            II
                                        </option>

                                        <option value="III"
                                            {{ $members->suffix == 'III' ? 'selected' : '' }}>
                                            III
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- BIRTHDATE -->
                    <div class="row mb-1">

                        <div class="col-md-4">

                            <div class="row mb-1">

                                <label class="col-sm-3 col-form-label">
                                    Date of birth
                                </label>

                                <div class="col-sm-9">

                                    <input type="date"
                                        name="birthdate"
                                        id="birthdate"
                                        value="{{ $members->birthdate }}"
                                        class="form-control">

                                </div>

                            </div>

                        </div>

                        <div class="col-md-2">

                            <div class="row mb-1">

                                <label class="col-sm-4 col-form-label">
                                    Age
                                </label>

                                <div class="col-sm-8">

                                    <input type="text"
                                        id="age"
                                        value="{{ \Carbon\Carbon::parse($members->birthdate)->age }}"
                                        class="form-control"
                                        readonly>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="row mb-1">

                                <label class="col-sm-2 col-form-label">
                                    Place of birth
                                </label>

                                <div class="col-sm-10">

                                    <input type="text"
                                        name="birthplace"
                                        value="{{ $members->birthplace }}"
                                        class="form-control"
                                        placeholder="Ex. Brgy, city, Region">

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- GENDER + MOBILE -->
                    <div class="row mb-1">

                        <div class="col-md-6">

                            <div class="row mb-1">

                                <label class="col-sm-2 col-form-label">
                                    Gender
                                </label>

                                <div class="col-sm-10">

                                    <select name="gender" class="form-control">

                                        <option value="Male"
                                            {{ $members->gender == 'Male' ? 'selected' : '' }}>
                                            Male
                                        </option>

                                        <option value="Female"
                                            {{ $members->gender == 'Female' ? 'selected' : '' }}>
                                            Female
                                        </option>

                                    </select>

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="row mb-1">

                                <label class="col-sm-2 col-form-label">
                                    Mobile
                                </label>

                                <div class="col-sm-10">

                                    <input type="text"
                                        name="mobile"
                                        value="{{ $members->mobile }}"
                                        class="form-control"
                                        placeholder="Mobile">

                                </div>

                            </div>

                        </div>

                    </div>

                    <br>

                    <!-- PARENTS -->
                    <h5 class="card-description"> Parents Info </h5>

                    <div class="row mb-1">

                        <!-- FATHER -->
                        <div class="col-md-6">

                            <div class="row mb-1">

                                <label class="col-sm-2 col-form-label">
                                    Father
                                </label>

                                <div class="col-sm-10">

                                    <input type="text"
                                        name="father_name"
                                        value="{{ $members->father_name }}"
                                        class="form-control"
                                        placeholder="Father's Full Name">

                                </div>

                            </div>

                        </div>

                        <!-- MOTHER -->
                        <div class="col-md-6">

                            <div class="row mb-1">

                                <label class="col-sm-2 col-form-label">
                                    Mother
                                </label>

                                <div class="col-sm-10">

                                    <input type="text"
                                        name="mother_name"
                                        value="{{ $members->mother_name }}"
                                        class="form-control"
                                        placeholder="Mother's Full Name">

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- ADDRESS (LIVEWIRE FIXED) -->
                    <h5 class="card-description">Address</h5>

                    <div class="card p-3 border-0 shadow-sm">

                    @livewire('address-picker', [
                        'memberId' => $members->id
                    ], key($members->id))

                    </div>
                    <br>

                    
                    <h5 class="card-description"> Other Info </h5>

                    <div class="row mb-2">

                        <!-- Church -->
                        <div class="col-md-6">

                            <div class="form-group row">

                                <label class="col-sm-2 col-form-label">
                                    Church
                                </label>

                                <div class="col-sm-10">

                                    <select name="church_id"
                                            class="form-control"
                                            required>

                                        <option value="">
                                            -- Select Church --
                                        </option>

                                        @foreach($churches as $church)

                                            <option value="{{ $church->id }}"
                                                {{ $members->church_id == $church->id ? 'selected' : '' }}>

                                                {{ $church->church_name }}
                                                -
                                                {{ $church->place }}

                                            </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>

                        <!-- MEMBERSHIP STATUS -->
                        <div class="col-md-6">

                            <div class="form-group row">

                                <label class="col-sm-3 col-form-label">
                                    Membership Status
                                </label>

                                <div class="col-sm-9 d-flex align-items-center gap-3">

                                    <!-- BAPTIZED -->
                                    <div class="form-check">

                                        <input class="form-check-input"
                                            type="radio"
                                            name="membership_status"
                                            id="statusBaptized"
                                            value="baptized"
                                            {{ $members->membership_status == 'baptized' ? 'checked' : '' }}>

                                        <label class="form-check-label"
                                            for="statusBaptized">

                                            Baptized

                                        </label>

                                    </div>

                                    <!-- DEDICATED -->
                                    <div class="form-check">

                                        <input class="form-check-input"
                                            type="radio"
                                            name="membership_status"
                                            id="statusDedicated"
                                            value="dedicated"
                                            {{ $members->membership_status == 'dedicated' ? 'checked' : '' }}>

                                        <label class="form-check-label"
                                            for="statusDedicated">

                                            Dedicated

                                        </label>

                                    </div>

                                    <!-- N/A -->
                                    <div class="form-check">

                                        <input class="form-check-input"
                                            type="radio"
                                            name="membership_status"
                                            id="statusNA"
                                            value="na"
                                            {{ $members->membership_status == 'na' ? 'checked' : '' }}>

                                        <label class="form-check-label"
                                            for="statusNA">

                                            N/A

                                        </label>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- BAPTISM FORM -->
                    <div id="baptismFields"
                        class="membership-box"
                        style="{{ $members->membership_status == 'baptized' ? '' : 'display:none;' }}">

                        <div class="card border-0 shadow-sm p-3 mt-3">

                            <h6 class="mb-3 text-success">
                                Baptism Information
                            </h6>

                            <div class="row mb-2">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Baptism Date
                                    </label>

                                    <input type="date"
                                        name="baptism_date"
                                        value="{{ $members->membership_status == 'baptized' ? $members->baptism_date : '' }}"
                                        class="form-control">

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Baptism Place
                                    </label>

                                    <input type="text"
                                        name="baptism_place"
                                        value="{{ $members->membership_status == 'baptized' ? $members->baptism_place : '' }}"
                                        class="form-control"
                                        placeholder="Place of Baptism">

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Minister
                                    </label>

                                    <input type="text"
                                        name="officiating_minister"
                                        value="{{ $members->membership_status == 'baptized' ? $members->officiating_minister : '' }}"
                                        class="form-control"
                                        placeholder="Officiating Minister">

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- DEDICATION FORM -->
                    <div id="dedicationFields"
                        class="membership-box"
                        style="{{ $members->membership_status == 'dedicated' ? '' : 'display:none;' }}">

                        <div class="card border-0 shadow-sm p-3 mt-3">

                            <h6 class="mb-3 text-primary">
                                Dedication Information
                            </h6>

                            <div class="row mb-2">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Dedication Date
                                    </label>

                                    <input type="date"
                                        name="dedication_date"
                                        value="{{ $members->membership_status == 'dedicated' ? $members->baptism_date : '' }}"
                                        class="form-control">

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Dedication Place
                                    </label>

                                    <input type="text"
                                        name="dedication_place"
                                        value="{{ $members->membership_status == 'dedicated' ? $members->baptism_place : '' }}"
                                        class="form-control"
                                        placeholder="Place of Dedication">

                                </div>

                            </div>

                            <div class="row">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Minister
                                    </label>

                                    <input type="text"
                                        name="dedication_minister"
                                        value="{{ $members->membership_status == 'dedicated' ? $members->officiating_minister : '' }}"
                                        class="form-control"
                                        placeholder="Officiating Minister">

                                </div>

                            </div>

                        </div>

                    </div>
                </div>    

                <br>

                <!-- BUTTONS -->
                <div class="d-flex justify-content-end me-4 pb-4">
                    <a href="{{ url('/members') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                    <button type="submit" class="btn btn-outline-success">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>

@vite(['resources/js/member/edit.js'])

@endsection