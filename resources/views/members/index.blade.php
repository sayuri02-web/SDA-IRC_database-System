@extends('layout')
@section('content')

<div class="col-lg-12 grid-margin stretch-card members-index-wrapper">
    <div class="card shadow-sm members-index-card">

        <!-- CARD HEADER -->
        <div class="card-body members-index-body">

            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">

                <div>
                    <h4 class="card-title mb-1">Registered Members List</h4>
                </div>

                <div class="d-flex align-items-center">

                    <!-- SEARCH -->
                    <div class="me-2">

                        <input type="text"
                            name="search"
                            id="searchInput"
                            class="form-control shadow-sm"
                            style="
                                    border-radius:10px;
                                    background:#f8f9fa;
                                    height:42px;
                                    min-width:250px;
                                    border:1px solid #98d7b5;
                            "
                            placeholder="Search member..."
                            value="">

                    </div>

                    <!-- ADD BUTTON -->
                    <a href="{{ url('/members/create')}}" class="btn btn-outline-success btn-sm">
                        <i class="fa fa-plus me-1"></i> Add New
                    </a>

                </div>

            </div>
            <hr class="mb-4">

            <!-- TABLE -->
            <div class="table-scroll-area">

                    <table class="table table-hover align-middle">

                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Cluster</th>
                                <th>Officiating Minister</th>
                                <th>Membership Status</th>
                                <th width="250px">Actions</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach($members as $item)

                            <tr class="member-row member-item"
                                data-name="{{ strtolower($item->full_name) }}"
                                data-cluster="{{ strtolower($item->church->cluster ?? '') }}">
                                <!-- PHOTO -->
                                <td class="py-1">

                                    @if($item->photo)

                                        <img src="{{ asset('uploads/' . $item->photo) }}"
                                            alt="image"
                                            style="
                                                width:45px;
                                                height:45px;
                                                object-fit:cover;
                                                border-radius:50%;
                                            ">

                                    @else

                                        <img src="https://via.placeholder.com/45"
                                            alt="image"
                                            style="
                                                width:45px;
                                                height:45px;
                                                object-fit:cover;
                                                border-radius:50%;
                                            ">

                                    @endif

                                </td>

                                <!-- NAME -->
                                <td>

                                    <div style="font-weight:600; font-size:15px;">
                                        {{ $item->full_name }}
                                    </div>

                                    <small class="text-muted d-block">
                                        <i class="fa fa-user me-1"></i>
                                        Age: {{ $item->age ?? '-' }}
                                    </small>

                                    <small class="text-muted d-block">
                                        <i class="fa fa-phone me-1"></i>
                                        {{ $item->mobile ?? '-' }}
                                    </small>

                                </td>

                                <!-- ADDRESS -->
                                <td>
                                    {{ $item->street }},
                                    {{ $item->barangay }},
                                    {{ $item->city }},
                                </td>

                                <!-- CLUSTER -->
                                <td>
                                    {{ $item->church->cluster ?? 'N/A' }}

                                    <small class="text-muted d-block">
                                        <i class="fa fa-building me-0"></i>
                                        {{ $item->church->church_name ?? 'No Church' }}
                                    </small>
                                </td>

                                <!-- MINISTER -->
                                <td>
                                    {{ $item->officiating_minister ?? 'N/A' }}
                                </td>

                                <!-- MEMBERSHIP STATUS -->
                                <td>

                                    @if($item->membership_status === 'baptized')

                                        <div class="status-pill baptized-status">

                                            <span class="status-dot"></span>

                                            <span>
                                                Baptized
                                            </span>

                                        </div>

                                    @elseif($item->membership_status === 'dedicated')

                                        <div class="status-pill dedicated-status">

                                            <span class="status-dot"></span>

                                            <span>
                                                Dedicated
                                            </span>

                                        </div>

                                    @else

                                        <div class="status-pill na-status">

                                            <span class="status-dot"></span>

                                            <span>
                                                N/A
                                            </span>

                                        </div>

                                    @endif

                                </td>

                                <!-- ACTIONS -->
                                <td>

                                <a href="{{ url('/members/' . $item->id) }}" class="btn btn-outline-info btn-sm"><i class="fa fa-eye"></i> View</a>

                                    <a href="{{ url('/members/' .$item->id .'/edit') }}">
                                        <button class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-pencil-square-o"></i> Edit
                                        </button>
                                    </a>

                                    <form method="POST"
                                        action="{{ url('/members' . '/' .$item->id)}}"
                                        style="display:inline">

                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}

                                        <button type="submit"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('confirm delete?')">

                                            <i class="fa fa-trash-o"></i> Delete
                                        </button>

                                    </form>

                                </td>

                            </tr>

                            @endforeach

                        </tbody>

                    </table>
            </div>    
        </div>
    </div>
</div>
@endsection