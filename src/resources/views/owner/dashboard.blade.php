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

    <a href="http://localhost/shop/create">店舗情報作成</a>
    <a href="">店舗情報管理</a>
    <a href="">予約情報管理</a>

</div>

@endsection