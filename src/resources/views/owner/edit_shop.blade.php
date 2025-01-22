@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/edit_shop.css') }}">
@endsection

@section('content')
<div class="reservation__alert">
    {{ session('status') }}
</div>

<div class="shop-info__content">
    <form action="{{ route('owner.shop.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <input type="hidden" name="shop_id" value="{{ $shop->id }}">

        <!-- 店舗名 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">店舗名</span>
                <span class="form__label--required">※</span>
            </div>
            <div class="form__group__input">
                <input type="text" name="shop_name" placeholder="店舗名" value="{{ old('shop_name', $shop->shop_name) }}">
            </div>
            <div class="form__error">
                @error('shop_name') {{ $message }} @enderror
            </div>
        </div>

        <!-- 店舗情報 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">店舗情報</span>
                <span class="form__label--required">※</span>
            </div>
            <div class="form__group-content">
                <div class="form__group__textarea">
                    <textarea name="description"
                        placeholder="店舗情報">{{ old('description', $shop->description) }}</textarea>
                </div>
                <div class="form__error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>


        <!-- Area -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">Area</span>
                <span class="form__label--required">※</span>
            </div>
            <div class="select__item-wrapper">
                <select class="select__item-select" name="area_id">
                    <option value="" disabled>Area</option>
                    @foreach($areas as $area)
                    <option value="{{ $area->id }}" @if(old('area_id', $shop->area_id) == $area->id) selected @endif>
                        {{ $area->area_name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Genre -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">Genre</span>
                <span class="form__label--required">※</span>
            </div>
            <div class="select__item-wrapper">
                <select class="select__item-select" name="genre_id">
                    <option value="" disabled>Genre</option>
                    @foreach($genres as $genre)
                    <option value="{{ $genre->id }}" @if(old('genre_id', $shop->genre_id) == $genre->id) selected
                        @endif>
                        {{ $genre->genre_name }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- 現在の画像 -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">現在の画像</span>
            </div>
            <div>
                @if ($shop->image_url)
                <img id="currentImage" src="{{ $shop->image_url }}" alt="現在の画像">
                @else
                <img id="currentImage" src="{{ env('BASE_URL') . '/images/shops/noimage.png' }}" alt="デフォルト画像">
                @endif
            </div>
        </div>

        <!-- 画像アップロード -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">画像</span>
            </div>
            <div class="form__group__input">
                <input type="file" name="image">
            </div>
            <div class="form__error">
                @error('image') {{ $message }} @enderror
            </div>
        </div>

        <!-- プレビュー -->
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">プレビュー</span>
            </div>
            <div>
                <img id="preview" src="#" alt="プレビュー" style="display: none;">
            </div>
        </div>

        <!-- ボタン -->
        <div class="form__button-group">
            <a href="/owner/shops" class="form__button-back">戻る</a>
            <button class="form__button-submit" type="submit" onclick="return confirm('この内容で確定しますか？')">変更を確定</button>
        </div>
    </form>
</div>

<script>
    const inputImage = document.querySelector('input[name="image"]');
    const preview = document.getElementById('preview');

    inputImage.addEventListener('change', () => {
        const file = inputImage.files[0];
        const reader = new FileReader();

        reader.onload = (e) => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    });
</script>
@endsection