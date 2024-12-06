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
                        <form action="/owner" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="owner_id" value="{{ $owner->id }}">
                            <button type="submit" onclick="return confirm('本当に削除しますか？')">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
