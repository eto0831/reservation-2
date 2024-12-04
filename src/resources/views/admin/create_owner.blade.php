@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/create_owner.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>
<div class="dashboard__content">
    <h1>admin</h1>
    <div class="register-form__heading">
        <h2>店舗代表者登録</h2>
    </div>
    <form class="form" action="/admin/create/owner" method="post">
        @csrf
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">氏名</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="text" name="name" value="{{ old('name') }}" />
                </div>
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">メールアドレス</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="email" name="email" value="{{ old('email') }}" />
                </div>
                <div class="form__error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">パスワード</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="password" name="password" />
                </div>
                <div class="form__error">
                    @error('password')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">確認用パスワード</span>
            </div>
            <div class="form__group-content">
                <div class="form__input--text">
                    <input type="password" name="password_confirmation" />
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">Area</span>
                <span class="form__label--required">※</span>
            </div>
            <div class="form__group-content">
                <div class="select__item-wrapper">
                    <select class="select__item-select" name="shop_id">
                        <option value="" selected>指定なし</option>
                        @foreach($shops as $shop)
                        <option value="{{ $shop->id }}">{{ $shop->id . " : " . $shop->shop_name . " : " . $shop->area->area_name . " : " . $shop->genre->genre_name  }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form__error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit">登録</button>
        </div>
    </form>
</div>
</div>



@endsection