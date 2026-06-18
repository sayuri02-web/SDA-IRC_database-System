@extends('layouts.app')

@section('title', 'Website Management')

@section('content')
<div class="container-fluid">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Website Management</h3>
            <p class="text-muted mb-0">
                Manage website content displayed on the church website.
            </p>
        </div>
    </div>

    <div class="row g-4">

        {{-- Pastor's Message --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">

                    <i class="mdi mdi-account-voice fs-1 text-primary"></i>

                    <h5 class="mt-3">
                        Pastor's Message
                    </h5>

                    <p class="text-muted small">
                        Manage the pastor's featured message for the website.
                    </p>

                    <button
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#pastorMessageModal">
                        Manage
                    </button>

                </div>
            </div>
        </div>

        {{-- Events & Announcements --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">

                    <i class="mdi mdi-calendar-month fs-1 text-success"></i>

                    <h5 class="mt-3">
                        Events & Announcements
                    </h5>

                    <p class="text-muted small">
                        Manage church events and announcements.
                    </p>

                    <button
                        class="btn btn-success"
                        data-bs-toggle="modal"
                        data-bs-target="#eventsModal">
                        Manage
                    </button>

                </div>
            </div>
        </div>

        {{-- Ministries --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">

                    <i class="mdi mdi-account-group fs-1 text-warning"></i>

                    <h5 class="mt-3">
                        Ministries
                    </h5>

                    <p class="text-muted small">
                        Manage church ministries and descriptions.
                    </p>

                    <button
                        class="btn btn-warning"
                        data-bs-toggle="modal"
                        data-bs-target="#ministriesModal">
                        Manage
                    </button>

                </div>
            </div>
        </div>

        {{-- Gallery --}}
        <div class="col-md-6 col-xl-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">

                    <i class="mdi mdi-image-multiple fs-1 text-danger"></i>

                    <h5 class="mt-3">
                        Gallery
                    </h5>

                    <p class="text-muted small">
                        Manage website photos and gallery albums.
                    </p>

                    <button
                        class="btn btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#galleryModal">
                        Manage
                    </button>

                </div>
            </div>
        </div>

    </div>

</div>

{{-- Pastor Message Modal --}}
<div class="modal fade" id="pastorMessageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Pastor's Message
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                Content goes here...
            </div>

        </div>
    </div>
</div>

{{-- Events Modal --}}
<div class="modal fade" id="eventsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Events & Announcements
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                Content goes here...
            </div>

        </div>
    </div>
</div>

{{-- Ministries Modal --}}
<div class="modal fade" id="ministriesModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Ministries
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                Content goes here...
            </div>

        </div>
    </div>
</div>

{{-- Gallery Modal --}}
<div class="modal fade" id="galleryModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Gallery
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body">
                Content goes here...
            </div>

        </div>
    </div>
</div>
@endsection