@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/reservation_index.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>


@endsection