@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/user_index.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>

<h2>店舗代表者一覧</h2>

<!-- テーブルコンテナ -->
<div class="owners__wrap">
    <table class="owners__table">
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
            {{-- 担当店舗があるオーナーの表示 --}}
            @foreach ($ownersWithShops as $owner)
                @php
                    $shopCount = $owner->shops->count();
                    $rowCount = $shopCount > 0 ? $shopCount : 1; // rowspanの値を設定
                    $firstRow = true; // 最初の行かどうかを判定するフラグ
                @endphp

                @foreach ($owner->shops as $shop)
                <tr>
                    @if ($firstRow)
                        <td rowspan="{{ $rowCount }}">{{ $loop->parent->iteration }}</td>
                        <td rowspan="{{ $rowCount }}">{{ $owner->name }}</td>
                        <td rowspan="{{ $rowCount }}">{{ $owner->email }}</td>
                        <td rowspan="{{ $rowCount }}">{{ $owner->created_at->format('Y-m-d') }}</td>
                        @php
                            $firstRow = false;
                        @endphp
                    @endif
                    <td>{{ $shop->id }}</td>
                    <td>{{ $shop->shop_name }}</td>
                    @if ($loop->first)
                        <td rowspan="{{ $rowCount }}">
                            <a href="{{ route('admin.owner.edit', ['owner_id' => $owner->id]) }}">編集</a>
                        </td>
                    @endif
                </tr>
                @endforeach
            @endforeach

            {{-- 担当店舗がないオーナーの表示 --}}
            @foreach ($ownersWithoutShops as $owner)
                <tr>
                    <td>{{ $loop->iteration + $ownersWithShops->count() }}</td>
                    <td>{{ $owner->name }}</td>
                    <td>{{ $owner->email }}</td>
                    <td>{{ $owner->created_at->format('Y-m-d') }}</td>
                    <td colspan="2">担当店舗なし</td>
                    <td>
                        <a href="{{ route('admin.owner.edit', ['owner_id' => $owner->id]) }}">編集</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

<div class="back__button">
    <a href="/admin/dashboard">戻る</a>
</div>
</div>
@endsection
