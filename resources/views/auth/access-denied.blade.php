@extends('layout')

@section('content')
<div class="d-flex align-items-center justify-content-center" style="flex:1; min-height:400px;">
    <div id="access-denied-app" data-role="{{ $currentRole }}" data-module="{{ $requiredModule }}"></div>
</div>
@endsection
