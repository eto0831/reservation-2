@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>
<div class="dashboard__content">
    <h1>owner</h1>

    <a href="http://localhost/shop/create"></a>

</div>

@endsection