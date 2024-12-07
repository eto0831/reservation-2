@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Email Notification</h1>
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
            <input type="text" name="subject" id="subject" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="message">本文</label>
            <textarea name="message" id="message" class="form-control" rows="5"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">メール送信</button>
    </form>
</div>
@endsection
