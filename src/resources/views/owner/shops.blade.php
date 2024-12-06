@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/shops.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>担当店舗一覧</h1>
    <div class="shop-section">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>店舗ID</th>
                    <th>店舗名</th>
                    <th>エリア</th>
                    <th>ジャンル</th>
                    <th>アクション</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shops as $shop)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $shop->id }}</td>
                    <td>{{ $shop->shop_name }}</td>
                    <td>{{ $shop->area->area_name }}</td>
                    <td>{{ $shop->genre->genre_name }}</td>
                    <td>
                        <!-- 更新フォーム -->
                        <form action="{{ route('shop.edit') }}" method="post" style="display:inline;">
                            @csrf
                            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                            <button type="submit">編集</button>
                        </form>
                        <!-- 削除フォーム -->
                        <form action="{{ route('shop.destroy') }}" method="post" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                            <button type="submit" onclick="return confirm('本当に削除しますか？')">削除</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">担当店舗はありません。</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection