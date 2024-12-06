@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin/edit_owner.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>
<div class="container">
    <h1>オーナー編集</h1>
    <div class="owner-edit __container">
        <form action="{{ route('admin.owner.update') }}" method="post">
            @csrf
            @method('PATCH')
            <input type="hidden" name="owner_id" value="{{ $owner->id }}">
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">氏名</span>
                </div>
                <div class="form__group-content">
                    <div class="form__input--text">
                        <input type="text" name="name" value="{{ old('name', $owner->name) }}" />
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
                        <input type="email" name="email" value="{{ old('email', $owner->email) }}" />
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
                    <span class="form__label--item">新しいパスワード</span>
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
                    <span class="form__label--item">担当店舗</span>
                    <span class="form__label--required">※</span>
                </div>
                <div class="form__group-content">
                    <div class="select__item-wrapper">
                        <select name="shop_ids[]" id="shop_ids" multiple>
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
                <button class="form__button-submit" type="submit">更新</button>
            </div>
        </form>
    </div>

</div>



@endsection