@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>

<div class="admin__content">
    <h2 class="admin__title">管理者ページ</h2>
    <div class="admin__links">
        <a class="admin__link" href="/admin/owner/create">店舗代表者作成</a>
        <a class="admin__link" href="/admin/user/index">オーナー管理</a>
        <a class="admin__link" href="{{ route('admin.emailNotification') }}">お知らせメール機能</a>
    </div>
</div>
@endsection
