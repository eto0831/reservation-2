@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/email-notification.css') }}">
@endsection

@section('content')
<div class="container">
    <h2 class="container__title">お知らせメール作成</h2>

    @if ($errors->any())
    <div class="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.sendNotification') }}">
        @csrf
        <div class="form-group">
            <label for="target">宛先</label>
            <select name="target" id="target" class="form-control">
                <option value="all">全員</option>
                <option value="user">ユーザー</option>
                <option value="owner">店舗代表者</option>
                <option value="admin">管理者</option>
            </select>
        </div>
        <div class="form-group">
            <label for="subject">件名</label>
            <input type="text" name="subject" id="subject" class="form-control">
        </div>
        <div class="form-group">
            <label for="message">本文</label>
            <textarea name="message" id="message" class="form-control" rows="5"></textarea>
        </div>
        <div class="button__group">
            <a href="/admin/dashboard" class="button button--secondary">戻る</a>
            <button type="submit" class="button button--primary" onclick="return confirm('この内容で送信しますか？')">メール送信</button>
        </div>
    </form>
</div>
@endsection
