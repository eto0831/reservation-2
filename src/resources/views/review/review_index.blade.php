@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review/review_index.css') }}">

@endsection

@section('content')
<div class="review-list__content">
    <div class="review-list__heading">
        <h2>{{ $shop->shop_name }}のレビュー一覧</h2>
    </div>
    <div class="review-info">
        <p class="shop__rating-inner">
            <span class="shop__rating-item">平均評価: {{ number_format($shop->avg_rating, 1) }} / 5</span>
            <span class="shop__rating-item">評価数: {{ $shop->reviews_count }} 件</span>
        </p>
    </div>

    @if ($reviews->isEmpty())
    <div class="review__message">
        <p class="review__message-item">レビューはまだありません</p>
    </div>
    @else
    <ul class="review__lists-container">
        @foreach($reviews as $review)
        <li class="review__lists-item">
            <div class="review-item">
                <p>投稿者：{{ $review->user->name }}</p>
                <p>評価：{{ $review->rating }} / 5</p>
                <p>コメント：{{ $review->comment }}</p>

                {{-- レビュー画像を表示 --}}
                @if ($review->review_image_url)
                <div class="review__img-container">
                    <img class="review__img-item" src="{{ Storage::url($review->review_image_url) }}" alt="レビュー画像"
                        style="max-width: 200px;">
                </div>
                @endif

            </div>

            {{-- adminだけ削除ボタンを表示 --}}
            @if (Auth::user() && Auth::user()->hasRole('admin'))
            <form class="delete-form" action="{{ route('review.delete') }}" method="post" style="display: inline;">
                @csrf
                @method('DELETE')
                <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                <input type="hidden" name="review_id" value="{{ $review->id }}">
                <button type="submit" class="delete-button" onclick="return confirm('この投稿を削除しますか？')">投稿を削除</button>
            </form>
            @endif
        </li>
        @endforeach
    </ul>
    @endif


    <a class="back-button" href="{{ route('detail', ['shop_id' => $shop->id]) }}">店舗詳細に戻る</a>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const imgItems = document.querySelectorAll('.review__img-item'); // すべての画像を取得

    imgItems.forEach(img => {
        img.addEventListener('click', function () {
            this.classList.toggle('fullscreen'); // クリック時に拡大/縮小を切り替え
        });
    });

    // フルスクリーン時に画像以外をクリックして閉じる
    document.addEventListener('click', function (e) {
        const fullscreenImg = document.querySelector('.review__img-item.fullscreen');
        if (fullscreenImg && !fullscreenImg.contains(e.target)) {
            fullscreenImg.classList.remove('fullscreen');
        }
    });
});

</script>
@endsection