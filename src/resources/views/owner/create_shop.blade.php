@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/create_shop.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>
<div class="shop-info__content">
    <form action="{{ route('owner.shop.store')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">店舗名</span>
                <span class="form__label--required">※</span>
            </div>
            <div class="form__group-content">
                <div class="form__group__input">
                    <input type="text" name="shop_name" placeholder="店舗名" value="{{ old('shop_name') }}">
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
                <span class="form__label--item">エリア</span>
                <span class="form__label--required">※</span>
            </div>
            <div class="form__group-content">
                <div class="select__item-wrapper">
                    <select class="select__item-select" name="area_id">
                        <option value="" disabled selected>エリアを選択</option>
                        @foreach($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->area_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form__error">
                    @error('area_id')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">ジャンル</span>
                <span class="form__label--required">※</span>
            </div>
            <div class="form__group-content">
                <div class="select__item-wrapper">
                    <select class="select__item-select" name="genre_id">
                        <option value="" disabled selected>ジャンルを選択</option>
                        @foreach($genres as $genre)
                        <option value="{{ $genre->id }}">{{ $genre->genre_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form__error">
                    @error('genre_id')
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
                <div class="form__group__textarea">
                    <textarea name="description" placeholder="店舗情報">{{ old('description') }}</textarea>
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
                <img id="preview" src="#" alt="プレビュー" style="display: none;">
            </div>
        </div>

        <div class="form__button-group">
            <a href="/owner/dashboard" class="form__button-back">戻る</a>
            <button class="form__button-submit" type="submit" onclick="return confirm('この内容で確定しますか？')">作成</button>
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
            preview.style.display = 'none';
        }
    });
</script>
@endsection
