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

    @if (!isset($review))
        {{-- **新規作成フォーム** --}}
        <form class="review__form" action="/review" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" name="reservation_id" value="{{ $reservationId }}">
            <div>
                <label for="rating">評価:</label>
                <select name="rating" id="rating">
                    @for ($i = 1; $i <=5; $i++)
                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="comment">コメント:</label>
                <textarea name="comment" placeholder="レビューは191文字以内の上、必ずご入力ください。">{{ old('comment') }}</textarea>
            </div>
            {{-- 画像アップロード --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">画像</span>
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
            <button type="submit" onclick="return confirm('この内容でレビューを投稿しますか？')">投稿</button>
        </form>
    @else
        {{-- **編集フォーム** --}}
        <form action="{{ route('review.update', $review->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div>
                <label for="rating">評価:</label>
                <select name="rating" id="rating">
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ (old('rating', $review->rating) == $i) ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="comment">コメント:</label>
                <textarea name="comment" id="comment">{{ old('comment', $review->comment) }}</textarea>
            </div>
            {{-- 現在の画像 --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">現在の画像</span>
                </div>
                <div>
                    @if ($review->review_image_url)
                        <img src="{{ $review->review_image_url }}" alt="現在の画像" style="max-width: 200px;">
                    @else
                        <p>画像はありません。</p>
                    @endif
                </div>
            </div>
            {{-- 画像アップロード --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="form__label--item">新しい画像</span>
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
            <button type="submit" onclick="return confirm('この内容で更新しますか？')">更新</button>
        </form>
    @endif

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
