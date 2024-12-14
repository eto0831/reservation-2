@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/edit_shop.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>
<div class="shop-info__content">
    <form action="{{ route('owner.shop.update') }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')
        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">店舗名</span>
                <span class="form__label--required">※</span>
            </div>
            <div class="form__group-content">
                <div class="form__group__input">
                    <input type="text" name="shop_name" placeholder="店舗名"
                        value="{{ old('shop_name', $shop->shop_name) }}">
                </div>
                <div class="form__error">
                    @error('shop_name')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">店舗情報</span>
                <span class="form__label--required">※</span>
            </div>
            <div class="form__group-content">
                <div class="form__group__input">
                    <input type="text" name="description" placeholder="店舗情報"
                        value="{{ old('description', $shop->description) }}">
                </div>
                <div class="form__error">
                    @error('description')
                    {{ $message }}
                    @enderror
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
                    <select class="select__item-select" name="area_id">
                        <option value="" disabled>Area</option>
                        @foreach($areas as $area)
                        <option value="{{ $area->id }}" @if(old('area_id', $shop->area_id) == $area->id) selected
                            @endif>
                            {{ $area->area_name }}
                        </option>
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
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">Genre</span>
                <span class="form__label--required">※</span>
            </div>
            <div class="form__group-content">
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
                <div class="form__error">
                    @error('description')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">現在の画像</span>
            </div>
            <div class="form__group-content">
                @if ($shop->image_url)
                <img id="currentImage" src="{{ asset($shop->image_url) }}" alt="現在の画像"
                    style="max-width: 200px; max-height: 200px;">
                @else
                <img id="currentImage" src="{{ env('BASE_URL') . '/images/shops/noimage.png' }}" alt="デフォルト画像"
                    style="max-width: 200px; max-height: 200px;">
                @endif
            </div>
        </div>

        <div class="form__group">
            <div class="form__group--title">
                <span class="form__label--item">画像</span>
            </div>
            <div class="form__group-content">
                <div class="form__group__input">
                    <input type="file" name="image">
                </div>
                <div class="form__error">
                    @error('image')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">プレビュー</span>
            </div>
            <div class="form__group-content">
                <img id="preview" src="#" alt="プレビュー" style="max-width: 200px; max-height: 200px; display: none;">
            </div>
        </div>
        <div class="form__button">
            <button class="form__button-submit" type="submit" onclick="return confirm('変更を確定しますか？')">変更</button>
        </div>
    </form>
    <!-- 削除フォーム -->
    <form action="{{ route('owner.shop.destroy') }}" method="post" style="display:inline;">
        @csrf
        @method('DELETE')
        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
        <button type="submit" onclick="return confirm('本当に削除しますか？')">削除</button>
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
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = '#';
            preview.style.display = 'none';
        }
    });
</script>

@endsection