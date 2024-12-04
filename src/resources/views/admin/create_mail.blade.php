@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/create_mail.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>


@endsection