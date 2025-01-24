@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review/create.css') }}">
@endsection

@section('content')
    <h1>{{ isset($review) ? 'レビューを編集する' : 'レビューを投稿する' }}</h1>

    {{-- エラーメッセージの表示 --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ isset($review) ? route('review.update', $review->id) : route('review.store') }}"
        method="post"
        enctype="multipart/form-data"
    >
        @csrf
        @if (isset($review))
            @method('PUT')
        @endif

        {{-- 隠しフィールド --}}
        <input type="hidden" name="shop_id" value="{{ $shop->id ?? $review->shop_id }}">
        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
        @if (!isset($review))
            <input type="hidden" name="reservation_id" value="{{ $reservationId }}">
        @endif

        {{-- 評価 --}}
        <div>
            <label for="rating">評価:</label>
            <select name="rating" id="rating">
                @for ($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}"
                        {{ old('rating', $review->rating ?? '') == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>

        {{-- コメント --}}
        <div>
            <label for="comment">コメント:</label>
            <textarea name="comment" id="comment" placeholder="レビューは191文字以内でご入力ください。">{{ old('comment', $review->comment ?? '') }}</textarea>
        </div>

        {{-- 現在の画像 --}}
        @if (isset($review) && $review->review_image_url)
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">現在の画像</span>
                </div>
                <div>
                    <img src="{{ $review->review_image_url }}" alt="現在の画像" style="max-width: 200px;">
                </div>
            </div>

            {{-- 画像削除オプション --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">画像の削除</span>
                </div>
                <div class="form__group-content">
                    <label>
                        <input type="checkbox" name="delete_image" value="1">
                        現在の画像を削除する
                    </label>
                </div>
            </div>
        @endif

        {{-- 画像アップロード --}}
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">画像アップロード</span>
            </div>
            <div class="form__group-content">
                <div class="form__group__input">
                    <input type="file" name="image" id="image">
                </div>
                <div class="form__error">
                    @error('image')
                    {{ $message }}
                    @enderror
                </div>
            </div>
        </div>

        {{-- プレビュー --}}
        <div class="form__group">
            <div class="form__group-title">
                <span class="form__label--item">プレビュー</span>
            </div>
            <div>
                <img id="preview" src="#" alt="プレビュー" style="display: none; max-width: 200px;">
            </div>
        </div>

        {{-- ボタン --}}
        <button type="submit" onclick="return confirm('{{ isset($review) ? 'この内容で更新しますか？' : 'この内容で投稿しますか？' }}')">
            {{ isset($review) ? '更新' : '投稿' }}
        </button>
    </form>

    {{-- プレビュー用のJavaScript --}}
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
