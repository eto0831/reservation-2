@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>
<h1>Admin</h1>


@endsection