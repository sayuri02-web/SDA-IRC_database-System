@extends('layout')

@section('content')

<div class="container mt-4">

    <div class="card shadow border-0">

        <div class="card-body p-4">

            <!-- HEADER -->
            <div class="text-center mb-3">

                <h2 class="font-weight-bold">
                    Baptism Certificate
                </h2>

                <p class="text-muted">
                    Review and complete certificate information
                </p>

            </div>

            <form method="POST"
                  action="{{ route('certificates.baptism.print') }}"  target="_blank">

                @csrf

                <input type="hidden"
                       name="member_id"
                       value="{{ $member->id }}">

                <!-- MEMBER INFO -->

                <div class="row">

                    <!-- FULL NAME -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Full Name
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ 
                                trim(
                                    $member->first_name . ' ' .
                                    ($member->middle_initial ? $member->middle_initial . ' ' : '') .
                                    $member->last_name . ' ' .
                                    ($member->suffix ?? '')
                                )
                               }}"
                               readonly>

                    </div>

                    <!-- GENDER -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Gender
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $member->gender }}"
                               readonly>

                    </div>

                </div>

                <div class="row">

                    <!-- BIRTHPLACE -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Born At
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $member->birthplace }}"
                               readonly>

                    </div>

                    <!-- BIRTHDATE -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Birth Date
                        </label>

                        <input type="date"
                               class="form-control"
                               value="{{ $member->birthdate }}"
                               readonly>

                    </div>

                </div>

                <div class="row">

                    <!-- FATHER -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Father Name
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $member->father_name }}"
                               readonly>

                    </div>

                    <!-- MOTHER -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Mother Name
                        </label>

                        <input type="text"
                               class="form-control"
                               value="{{ $member->mother_name }}"
                               readonly>

                    </div>

                </div>

                <hr class="my-4">

                <!-- BAPTISM INFO -->

                <h5 class="text-success mb-2">
                    Baptism Information
                </h5>

                <div class="row">
                    
                    <!-- BAPTISM PLACE -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Baptism Place
                        </label>

                        <input type="text"
                               name="baptism_place"
                               class="form-control"
                               value="{{ $member->baptism_place }}">

                    </div>

                </div>

                <div class="row">

                    <!-- MINISTER -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Officiating Minister
                        </label>

                        <input type="text"
                               name="officiating_minister"
                               class="form-control"
                               value="{{ $member->officiating_minister }}">

                    </div>

                    <!-- CHAIRMAN -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Chairman of Organization
                        </label>

                        <input type="text"
                               name="chairman"
                               class="form-control">

                    </div>

                </div>

                <div class="row">

                    <!-- SECRETARY -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Church Secretary
                        </label>

                        <input type="text"
                               name="secretary"
                               class="form-control">

                    </div>

                    <!-- FELLOWSHIP DATE -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Fellowship Date
                        </label>

                        <input type="date"
                               name="fellowship_date"
                               class="form-control">

                    </div>

                </div>

                <hr class="my-4">

                <h5 class="text-success mb-4">
                    Church Fellowship Information
                </h5>

                <div class="row">

                    <!-- DAY -->
                    <div class="col-md-4 mb-3">

                        <label>
                            Baptism Day
                        </label>

                        <input type="number"
                                name="baptism_day"
                                class="form-control"
                                value="{{ \Carbon\Carbon::parse($member->baptism_date)->format('d') }}">

                    </div>

                    <!-- MONTH -->
                    <div class="col-md-4 mb-3">

                        <label>
                            Baptism Month
                        </label>

                        <input type="text"
                                name="baptism_month"
                                class="form-control"
                                value="{{ \Carbon\Carbon::parse($member->baptism_date)->format('F') }}">

                    </div>

                    <!-- YEAR -->
                    <div class="col-md-4 mb-3">

                        <label>
                            Baptism Year
                        </label>

                        <input type="number"
                                name="baptism_year"
                                class="form-control"
                                value="{{ \Carbon\Carbon::parse($member->baptism_date)->format('Y') }}">

                    </div>

                </div>

                <div class="row">

                    <!-- CHURCH FELLOWSHIP -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Received Into Church Fellowship Of
                        </label>

                        <input type="text"
                            name="church_fellowship"
                            class="form-control">

                    </div>

                    <!-- FELLOWSHIP DATE -->
                    <div class="col-md-6 mb-3">

                        <label>
                            Fellowship Date
                        </label>

                        <input type="date"
                            name="fellowship_date"
                            class="form-control">

                    </div>

                </div>

                <hr class="my-4">

                <h5 class="text-success mb-4">
                    Document Information
                </h5>

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label>
                            Doc. No.
                        </label>

                        <input type="text"
                            name="doc_no"
                            class="form-control">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>
                            Page No.
                        </label>

                        <input type="text"
                            name="page_no"
                            class="form-control">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>
                            Book No.
                        </label>

                        <input type="text"
                            name="book_no"
                            class="form-control">

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>
                            Series Of
                        </label>

                        <input type="text"
                            name="series_no"
                            class="form-control">

                    </div>

                </div>

                <!-- BUTTON -->
                <div class="text-center mt-4">

                    <button type="submit"
                            class="btn btn-success btn-lg px-5">

                        Generate Certificate

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection