@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/edit_reservation.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>


@endsection