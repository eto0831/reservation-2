@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/shop-card.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>

<div class="attendance__content">
    <div class="search-form__wrapper">
        <form class="search-form" action="/search" method="get">
            @csrf
            <div class="contact-search">
                <select class="search-form__item-select area-select" name="area_id">
                    <option value="">All area</option>
                    @foreach ($areas as $area)
                    <option value="{{ $area->id }}" @if(request('area_id')==$area->id) selected @endif>
                        {{ $area->area_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="contact-search">
                <select class="search-form__item-select genre-select" name="genre_id">
                    <option value="">All genre</option>
                    @foreach ($genres as $genre)
                    <option value="{{ $genre->id }}" @if(request('genre_id')==$genre->id) selected @endif>{{
                        $genre->genre_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="name-search">
                <input type="text" class="search-form__item-input" placeholder="Search..." name="keyword"
                    value="{{ request('keyword') ?? old('keyword') }}">
            </div>
            {{-- <div class="search-form__button">
                <button class="search-form__button-submit" type="submit">検索</button>
            </div> --}}
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