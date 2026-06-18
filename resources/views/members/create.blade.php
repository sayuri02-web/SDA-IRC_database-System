@extends('layout')
@section('content')

    <div class="col-12 create-page-wrapper">

        {{-- TRANSACTION HEADER (fixed, never scrolls) --}}
        <div class="church-breadcrumb mb-3">
            @if(!empty($isLeader))
            <a href="{{ url('/leaders-directory') }}" class="church-breadcrumb-link">
                <i class="mdi mdi-account-tie me-1"></i>Leaders Directory
            </a>
            @else
            <a href="{{ url('/members') }}" class="church-breadcrumb-link">
                <i class="mdi mdi-account-group me-1"></i>Members
            </a>
            @endif
            <i class="mdi mdi-chevron-right mx-1 church-breadcrumb-sep"></i>
            <span class="church-breadcrumb-current">{{ !empty($isLeader) ? 'Add Officer' : 'Add New Member' }}</span>
        </div>

        <div class="card create-member-card">
            <div class="church-form-header" @if(!empty($isLeader)) style="background: linear-gradient(135deg, #2449d8, #5c7cfa);" @endif>
                <div class="church-form-header-icon">
                    <i class="mdi {{ !empty($isLeader) ? 'mdi-account-tie' : 'mdi-account-plus' }}"></i>
                </div>
                <div>
                    <h4 class="church-form-title mb-0">{{ !empty($isLeader) ? 'Add Officer' : 'Add New Member' }}</h4>
                    <p class="church-form-subtitle mb-0">{{ !empty($isLeader) ? 'Add a new officer to ' . $organization : 'Fill in the details to register a new church member' }}</p>
                </div>
            </div>

            <div class="card-body create-member-body">
                <form action="{{ url('members') }}" method="post" enctype="multipart/form-data" class="form-sample create-form">
                    {!! csrf_field()!!}

                    {{-- Leader hidden fields --}}
                    @if(!empty($isLeader))
                    <input type="hidden" name="is_leader" value="1">
                    <input type="hidden" name="organization" value="{{ $organization }}">
                    @endif

                    <div class="create-form-scroll">

                        <h5 class="card-description"> Personal Info </h5>
                        
                        <!--PHOTO UPLOADING HERE-->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Photo</label>

                                    <div class="col-sm-10">

                                        <input type="file"
                                            name="photo"
                                            id="photoInput"
                                            accept="image/*"
                                            class="file-upload-default"
                                            hidden>

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
                                            <img id="cropImage" style="max-width:100%; display:none;">
                                        </div>

                                        <button type="button"
                                                id="cropBtn"
                                                class="btn btn-outline-success btn-sm mt-2"
                                                style="display:none;">Crop 2x2
                                        </button>

                                        <div id="preview-container"
                                            style="
                                                margin-top:10px;
                                                position:relative;
                                                display:inline-block;
                                            ">

                                            <img id="preview"
                                                style="
                                                    display:none;
                                                    width:100px;
                                                    height:100px;
                                                    object-fit:cover;
                                                    border-radius:10px;
                                                    border:2px solid #198754;
                                                ">

                                            <button type="button"
                                                    id="remove-photo"
                                                    style="position:absolute; top:-10px; right:-10px;">
                                                ×
                                            </button>

                                        <!-- hidden input that will store cropped image -->
                                        <input type="hidden" name="cropped_photo" id="cropped_photo">

                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="row mb-2">

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">First Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="first_name" class="form-control" placeholder="Firstname" required />
                                    </div>
                                </div>
                            </div>

                            <!-- LAST NAME -->
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Last Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="last_name" class="form-control" placeholder="Lastname" required />
                                    </div>
                                </div>
                            </div>

                        </div>
                        
                        <!-- MIDDLE INITIAL -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">M.I.</label>
                                    <div class="col-sm-3">
                                        <input type="text" name="middle_initial" class="form-control" maxlength="1" placeholder="M.I." />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Suffix</label>
                                    <div class="col-sm-3">

                                        <select name="suffix" class="form-control">
                                            <option value="">None</option>
                                            <option value="Jr">Jr</option>
                                            <option value="Sr">Sr</option>
                                            <option value="II">II</option>
                                            <option value="III">III</option>
                                        </select>

                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Birthdate, Age & Birthplace -->
                        <div class="row mb-2">

                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Date of birth</label>
                                    <div class="col-sm-9">
                                        <input type="date"
                                            name="birthdate"
                                            id="birthdate"
                                            class="form-control"
                                            required />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label">Age</label>
                                    <div class="col-sm-8">
                                        <input type="text"
                                            id="age"
                                            class="form-control"
                                            readonly />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Place of birth</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="birthplace" class="form-control" placeholder="Ex. Brgy, city, Region" required />
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Gender & MOBILE -->
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Gender</label>
                                    <div class="col-sm-10">
                                        <select name="gender" class="form-control" required>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Mobile</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="mobile" class="form-control" required />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5 class="card-description"> Parents Info </h5>

                        <div class="row mb-2">

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Father</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            name="father_name"
                                            class="form-control"
                                            placeholder="Father's Full Name" required />
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Mother</label>
                                    <div class="col-sm-10">
                                        <input type="text"
                                            name="mother_name"
                                            class="form-control"
                                            placeholder="Mother's Full Name" required />
                                    </div>
                                </div>
                            </div>

                        </div>

                        <h5 class="card-description">Address</h5>
                        <div class="card p-3 border-0 shadow-sm">
                            @livewire('address-picker')
                        </div>

                        {{-- ORGANIZATION INFO (only for leaders) --}}
                        @if(!empty($isLeader))
                        <h5 class="card-description mt-4" style="color:#2449d8;"> Organization Information </h5>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Organization</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" value="{{ $organization }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">Position</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="position" class="form-control" placeholder="e.g. President, Secretary" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <h5 class="card-description mt-4"> Other Info </h5>

                        <!-- Church -->
                        <div class="row mb-2">

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

                                                <option value="{{ $church->id }}">

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
                                                value="baptized">

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
                                                value="dedicated">

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
                                                checked>

                                            <label class="form-check-label"
                                                for="statusNA">

                                                N/A

                                            </label>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- BAPTISM FORM -->
                            <div id="baptismFields"
                                class="membership-box"
                                style="display:none;">

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
                                                id="baptism_date"
                                                class="form-control">

                                        </div>

                                        <div class="col-md-6">

                                            <label class="form-label">
                                                Baptism Place
                                            </label>

                                            <input type="text"
                                                name="baptism_place"
                                                id="baptism_place"
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
                                                id="officiating_minister"
                                                class="form-control"
                                                placeholder="Officiating Minister">

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <!-- DEDICATION FORM -->
                            <div id="dedicationFields"
                                class="membership-box"
                                style="display:none;">

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
                                                id="dedication_date"
                                                class="form-control">

                                        </div>

                                        <div class="col-md-6">

                                            <label class="form-label">
                                                Dedication Place
                                            </label>

                                            <input type="text"
                                                name="dedication_place"
                                                id="dedication_place"
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
                                                id="dedication_minister"
                                                class="form-control"
                                                placeholder="Officiating Minister">

                                        </div>

                                    </div>

                                </div>

                            </div>
                    </div>

                    <br>
                    <!-- BUTTONS OUTSIDE SCROLL -->
                    <div class="create-form-footer d-flex justify-content-end me-4 py-3">
                        <a href="{{ !empty($isLeader) ? url('/leaders-directory') : url('/members') }}" class="btn btn-outline-secondary me-2">
                            Cancel
                        </a>

                        <button type="submit" class="btn btn-outline-success">
                            {{ !empty($isLeader) ? 'Save Officer' : 'Proceed' }}
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection