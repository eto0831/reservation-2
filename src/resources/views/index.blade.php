@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/shop-card.css') }}">
@endsection

@section('content')
<div class="reservation__alert">
    {{ session('status') }}
</div>

<div class="reservation__content">
    <!-- ソートフォームを検索フォームの上に追加 -->
    <div class="search-form__wrapper">
        <form class="search-form" action="/search" method="get">
            @csrf
            <!-- エリア選択 -->
            <div class="contact-search">
                <select class="search-form__item-select area-select" name="area_id" onchange="this.form.submit()">
                    <option value="" selected>All area</option>
                    @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @if(request('area_id')==$area->id) selected @endif>
                        {{ $area->area_name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- ジャンル選択 -->
            <div class="contact-search">
                <select class="search-form__item-select genre-select" name="genre_id" onchange="this.form.submit()">
                    <option value="" selected>All genre</option>
                    @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" @if(request('genre_id')==$genre->id) selected @endif>
                        {{ $genre->genre_name }}</option>
                    @endforeach
                </select>
            </div>
            <!-- キーワード検索 -->
            <div class="name-search">
                <input type="text" class="search-form__item-input" placeholder="Search..." name="keyword"
                    value="{{ request('keyword') ?? old('keyword') }}">
            </div>
            <!-- ソート選択を追加 -->
            <div class="contact-search">
                <select class="search-form__item-select sort-select" name="sort" onchange="this.form.submit()">
                    <option value="random" @if(request('sort')=='random' ) selected @endif>ランダム</option>
                    <option value="high_rating" @if(request('sort')=='high_rating' ) selected @endif>評価が高い順</option>
                    <option value="low_rating" @if(request('sort')=='low_rating' ) selected @endif>評価が低い順</option>
                </select>
            </div>
        </form>
    </div>

    <div class="shop__wrap">
        @foreach ($shops as $shop)
        @include('components.shop-card', ['shop' => $shop])
        @endforeach
    </div>
    {{ $shops->withQueryString()->links('vendor.pagination.custom') }}
</div>
@endsection
