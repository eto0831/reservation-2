@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/shops.css') }}">
@endsection

@section('content')
<div class="reservation__alert">
    {{ session('status') }}
</div>

<div class="container">
    <h1 class="shop__title">担当店舗一覧</h1>
    <div class="shop-section">
        <table class="shop-table">
            <thead>
                <tr class="shop-table__header">
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
                <tr class="shop-table__row">
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $shop->id }}</td>
                    <td>{{ $shop->shop_name }}</td>
                    <td>{{ $shop->area->area_name }}</td>
                    <td>{{ $shop->genre->genre_name }}</td>
                    <td class="shop-table__actions">
                        <a href="{{ route('owner.shop.edit', ['shop_id' => $shop->id]) }}" class="action-button action-button--edit">編集</a>
                        <form action="{{ route('owner.shop.destroy') }}" method="post" class="action-form">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                            <button type="submit" class="action-button action-button--delete" onclick="return confirm('本当に削除しますか？')">削除</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="shop-table__empty">担当店舗はありません。</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="back__button">
    <a href="/owner/dashboard" class="back-button">戻る</a>
</div>
@endsection
