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
    <table class="owners__table" border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>オーナー名</th>
                <th>Email</th>
                <th>登録日</th>
                <th>担当店舗ID</th>
                <th>担当店舗名</th>
                <th>アクション</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($owners as $owner)
                @php
                    $firstRow = true; // 最初の行かどうかを判定するフラグ
                @endphp
                @foreach ($owner->shops as $shop)
                <tr>
                    @if ($firstRow)
                        <td rowspan="{{ $owner->shops->count() }}">{{ $loop->parent->iteration }}</td>
                        <td rowspan="{{ $owner->shops->count() }}">{{ $owner->name }}</td>
                        <td rowspan="{{ $owner->shops->count() }}">{{ $owner->email }}</td>
                        <td rowspan="{{ $owner->shops->count() }}">{{ $owner->created_at->format('Y-m-d') }}</td>
                        @php
                            $firstRow = false;
                        @endphp
                    @endif
                    <td>{{ $shop->id }}</td>
                    <td>{{ $shop->shop_name }}</td>
                    @if ($loop->first)
                        <td rowspan="{{ $owner->shops->count() }}">
                            <form action="/owner/edit/{{ $owner->id }}" class="owner__edit" method="get">
                                <button type="submit">編集</button>
                            </form>
                            <form action="/owner" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                                <button type="submit" onclick="return confirm('本当に削除しますか？')">削除</button>
                            </form>
                        </td>
                    @endif
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endsection
