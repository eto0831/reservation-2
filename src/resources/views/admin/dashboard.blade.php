@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>
<h1>Admin</h1>
<a href="/admin/owner/create">店舗代表者作成</a>
<a href="/admin/user/index">ユーザーインデックス</a>


@endsection