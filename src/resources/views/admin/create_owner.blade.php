@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/create_owner.css') }}">
@endsection

@section('content')
<div class="dashboard__content">
    <div class="register-form__heading">
        <h2>店舗代表者登録</h2>
    </div>
    <form class="form" action="{{ route('admin.owner.store') }}" method="post">
        @csrf
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">氏名</span>
            </div>
            <div class="form__input--text">
                <input type="text" name="name" value="{{ old('name') }}" />
            </div>
            <div class="form__error">
                @error('name') {{ $message }} @enderror
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">メールアドレス</span>
            </div>
            <div class="form__input--text">
                <input type="email" name="email" value="{{ old('email') }}" />
            </div>
            <div class="form__error">
                @error('email') {{ $message }} @enderror
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">パスワード</span>
            </div>
            <div class="form__input--text">
                <input type="password" name="password" />
            </div>
            <div class="form__error">
                @error('password') {{ $message }} @enderror
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">担当店舗</span>
                <span class="form__label--required">※ある場合のみ</span>
            </div>
            <div class="select__item-wrapper">
                <select class="select__item-select" name="shop_id">
                    <option value="" selected>指定なし</option>
                    @foreach($shops as $shop)
                    <option value="{{ $shop->id }}">
                        {{ $shop->id . " : " . $shop->shop_name . " : " . $shop->area->area_name . " : " . $shop->genre->genre_name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form__error">
                @error('description') {{ $message }} @enderror
            </div>
        </div>

        <div class="form__button-group">
            <a href="/admin/dashboard" class="form__button-back">戻る</a>
            <button class="form__button-submit" type="submit" onclick="return confirm('この内容で登録しますか？')">登録</button>
        </div>
    </form>
</div>
@endsection
