@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review/create.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/shop-card.css') }}">
@endsection

@section('content')


{{-- エラーメッセージの表示 --}}
@if ($errors->review->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->review->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="review-form__wrapper">
    <div class="shop-card__box">
        <h2 class="shop-card__box-heading">{{ isset($review) ? '口コミを編集する' : '今回のご利用はいかがでしたか？' }}</h2>
        @include('components.shop-card', ['shop' => $shop])
    </div>
    <div class="review-form__content">
        <form class="review__form"
            action="{{ isset($review) ? route('review.update', $review->id) : route('review.store') }}" method="post"
            enctype="multipart/form-data">
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

            {{-- 評価（星形式） --}}
            <div class="review__form-items">
                <label class="review__form-label-item" for="rating">体験を評価してください</label>
                <div class="rating-stars" id="rating-stars">
                    @for ($i = 1; $i <= 5; $i++) <span
                        class="star {{ old('rating', $review->rating ?? 0) >= $i ? 'selected' : '' }}"
                        data-value="{{ $i }}">
                        ★</span>
                        @endfor
                </div>
                <input type="hidden" name="rating" id="rating" value="{{ old('rating', $review->rating ?? '') }}">
            </div>

            {{-- コメント --}}
            <div class="review__form-items">
                <label class="review__form-label-item" for="comment">口コミを投稿</label>
                <textarea class="review__form-items-comment" name="comment" id="comment"
                    placeholder="カジュアルな夜のお出かけにお勧めのスポット">{{ old('comment', $review->comment ?? '') }}</textarea>
            </div>

            {{-- 現在の画像 --}}
            @if (isset($review) && $review->review_image_url)
            <div class="review__form-items">
                <div class="review__form-items-title">
                    <span class="form__label--item">現在の画像</span>
                </div>
                <div class="current__image">
                    <img class="current__image-img" src="{{ Storage::url($review->review_image_url) }}" alt="現在の画像"
                        style="max-width: 200px;">
                </div>
            </div>

            {{-- 画像削除オプション --}}
            <div class="review__form-items">
                <div class="review__form-items-title">
                    <span class="review__form-label-item">画像の削除</span>
                </div>
                <div class="review__form-items-title">
                    <label class="review__form-label-item">
                        <input class="review__form-items-checkbox" type="checkbox" name="delete_image" value="1">
                        現在の画像を削除する
                    </label>
                </div>
            </div>
            @endif

            {{-- 画像アップロード（ドラッグアンドドロップ対応） --}}
            <div class="review__form-items">
                <div class="review__form-items-title">
                    <span class="review__form-label-item">画像の追加</span>
                </div>
                <div class="review__form-items">
                    {{-- ファイル入力は非表示 --}}
                    <input type="file" name="review_image_url" id="image" style="display: none;">
                    {{-- ドラッグアンドドロップエリア --}}
                    <div class="drag-drop-area" id="drag-drop-area">
                        ここにファイルをドラッグ＆ドロップ<br>またはクリックして選択
                        <img id="preview" src="#" alt="プレビュー">
                    </div>
                    <div class="form__error">
                        @error('image')
                        {{ $message }}
                        @enderror
                    </div>
                </div>
            </div>

            {{-- ボタン --}}
            <div class="review-form__button">
                <button class="review-form__button-submit" type="submit"
                    onclick="return confirm('{{ isset($review) ? 'この内容で更新しますか？' : 'この内容で投稿しますか？' }}')">
                    {{ isset($review) ? '口コミを更新' : '口コミを投稿' }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- プレビュー用のJavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
            const inputImage = document.getElementById('image');
            const dragDropArea = document.getElementById('drag-drop-area');
            const preview = document.getElementById('preview');

            // ファイルが選択されたとき
            inputImage.addEventListener('change', () => {
                handleFiles(inputImage.files);
            });

            // ドラッグオーバー
            dragDropArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                dragDropArea.classList.add('dragover');
            });

            // ドラッグアウト
            dragDropArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dragDropArea.classList.remove('dragover');
            });

            // ドロップ
            dragDropArea.addEventListener('drop', function(e) {
                e.preventDefault();
                dragDropArea.classList.remove('dragover');
                const files = e.dataTransfer.files;
                handleFiles(files);
                inputImage.files = files; // ファイルをinputにセット
            });

            // クリックでファイル選択
            dragDropArea.addEventListener('click', function() {
                inputImage.click();
            });

            function handleFiles(files) {
                if (files.length > 0) {
                    const file = files[0];

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        reader.onload = (e) => {
                            preview.src = e.target.result;
                            preview.style.display = 'block';
                        };

                        reader.readAsDataURL(file);
                    } else {
                        alert('画像ファイルを選択してください。');
                        inputImage.value = ''; // 入力をリセット
                        preview.src = '#';
                        preview.style.display = 'none';
                    }
                }
            }

            // 星の選択
            const stars = document.querySelectorAll('.rating-stars .star');
            const ratingInput = document.getElementById('rating');

            stars.forEach(star => {
                star.addEventListener('click', function() {
                    const value = this.getAttribute('data-value');

                    stars.forEach(s => s.classList.remove('selected'));
                    for (let i = 0; i < value; i++) {
                        stars[i].classList.add('selected');
                    }

                    ratingInput.value = value;
                });
            });
        });
</script>
@endsection