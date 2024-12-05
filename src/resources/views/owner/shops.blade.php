@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/shops.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>担当店舗一覧</h1>
    @forelse ($shops as $shop)
    <div class="shop-section">
        <h2>{{ $shop->shop_name }}</h2>
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
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $shop->id }}</td>
                    <td>{{ $shop->shop_name }}</td>
                    <td>{{ $shop->area->area_name }}</td>
                    <td>{{ $shop->genre->genre_name }}</td>
                    <td>
                        <!-- 更新フォーム -->
                        <form action="{{ route('shop.edit', $shop->id) }}" method="get" style="display:inline;">
                            @csrf
                            <button type="submit">編集</button>
                        </form>
                        <!-- 削除フォーム -->
                        <form action="{{ route('shop.destroy', $shop->id) }}" method="post" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">削除</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @empty
    <p>担当店舗はありません。</p>
    @endforelse
</div>
@endsection