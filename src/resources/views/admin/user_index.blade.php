@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/user_index.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>


@endsection