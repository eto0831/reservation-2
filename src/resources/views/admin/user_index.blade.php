@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/user_index.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>
<h2>Owners Index</h2>
<h2>オーナー一覧</h2>
<div class="owners__wrap">
    @foreach ($owners as $owner)
    <div class="owner__contents">
        <div class="owner__header">
            <h3>オーナー {{ $loop->iteration }}</h3>
            <div class="owner__menus">
                <form action="/owner/edit/{{ $owner->id }}" class="owner__edit" method="get">
                    <button type="submit">編集</button>
                </form>
                <form action="/owner" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                    <button type="submit">削除</button>
                </form>
            </div>
        </div>
        <h4>{{ $owner->name }}</h4>
        <p>Email: {{ $owner->email }}</p>
        <p>登録日: {{ $owner->created_at->format('Y-m-d') }}</p>
    </div>
    @endforeach
</div>
</div>

@endsection
