@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components/shop-card.css') }}">
@endsection

@section('header')
    <div class="header-utilities">
        <div class="search-form__wrapper">
            <form class="search-form" action="/search" method="get">
                @csrf
                <div class="search-form__box">
                    <!-- ソート選択を追加 -->
                    <div class="category-search">
                        <select class="search-form__item-select sort-select" name="sort" onchange="this.form.submit()">
                            <!-- 初期値（並び替え：評価高/低） -->
                            <option value="" hidden {{ !request('sort') ? 'selected' : '' }}>並び替え：評価高/低</option>
                            <!-- 選択肢 -->
                            <option value="random" {{ request('sort') == 'random' ? 'selected' : '' }}>ランダム</option>
                            <option value="high_rating" {{ request('sort') == 'high_rating' ? 'selected' : '' }}>評価が高い順
                            </option>
                            <option value="low_rating" {{ request('sort') == 'low_rating' ? 'selected' : '' }}>評価が低い順
                            </option>
                        </select>
                    </div>
                </div>
                <div class="search-form__box">
                    <!-- エリア選択 -->
                    <div class="category-search">
                        <select class="search-form__item-select area-select" name="area_id" onchange="this.form.submit()">
                            <option value="" selected>All area</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}" @if (request('area_id') == $area->id) selected @endif>
                                    {{ $area->area_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- ジャンル選択 -->
                    <div class="category-search">
                        <select class="search-form__item-select genre-select" name="genre_id" onchange="this.form.submit()">
                            <option value="" selected>All genre</option>
                            @foreach ($genres as $genre)
                                <option value="{{ $genre->id }}" @if (request('genre_id') == $genre->id) selected @endif>
                                    {{ $genre->genre_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- キーワード検索 -->
                    <div class="name-search">
                        <input type="text" class="search-form__item-input" placeholder="Search..." name="keyword"
                            value="{{ request('keyword') ?? old('keyword') }}">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection


@section('content')
    <div class="reservation__alert">
        {{ session('status') }}
    </div>

    <div class="reservation__content">
        <!-- ソートフォームを検索フォームの上に追加 -->

        <div class="shop__wrap">
            @foreach ($shops as $shop)
                @include('components.shop-card', ['shop' => $shop])
            @endforeach
        </div>

    </div>

    <script src="{{ asset('js/index.js') }}"></script>
@endsection
