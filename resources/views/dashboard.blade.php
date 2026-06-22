@extends('layout')

@section('content')

<div id="vue-dashboard"></div>

@endsection

@push('scripts')
@vite('resources/js/dashboard.js')
@endpush
