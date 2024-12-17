@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>
<div class="dashboard__content">
    <h1 class="dashboard__title">店舗代表者ページ</h1>

    <div class="dashboard__links">
        <a class="dashboard__link" href="/owner/shop/create">店舗情報作成</a>
        <a class="dashboard__link" href="/owner/shops">店舗情報編集</a>
        <a class="dashboard__link" href="/owner/reservations">予約情報管理</a>
        <a class="dashboard__link" href="/reservation/scan">来店確認機能</a>
    </div>
</div>
@endsection
