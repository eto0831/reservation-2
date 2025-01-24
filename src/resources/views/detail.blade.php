@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="reservation__alert">
    {{ session('status') }}
</div>

<div class="detail__content">
    <div class="detail__wrap">
        @if ($errors->hasBag('review'))
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->review->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="shop__info">
            <div class="shop__heading">
                <a href="/">&lt;</a>
                <h2>{{ $shop->shop_name }}</h2>
            </div>
            <div class="shop__img-container">
                @if ($shop->image_url)
                <img src="{{ $shop->image_url }}" alt="{{ $shop->shop_name }}" class="shop__img">
                @else
                <img src="{{ env('BASE_URL') . '/images/shops/noimage.png' }}" alt="デフォルト画像" class="shop__img">
                @endif
            </div>
            <div class="shop__categories">
                <p class="shop__categories-content">
                    <span>#{{ $shop->area->area_name }}</span>
                    <span>#{{ $shop->genre->genre_name }}</span>
                </p>
            </div>
            <div class="shop__description">
                <p>{{ $shop->description }}</p>
            </div>
            <div class="shop__rating">
                <p>平均評価: {{ number_format($shop->avg_rating, 1) }} / 5</p>
                <p>評価数: {{ $shop->reviews_count }} 件</p>
            </div>
        </div>
        <div class="review__wrap">
            @if (Auth::check() && $reservationId = Auth::user()->isVisited($shop->id))
                <p>この店舗は訪問済みです。</p>
        
                {{-- レビュー済みかどうかをチェック --}}
                @if (!$shop->hasReviewed(Auth::user()->id))
                    <a href="{{ route('review', $shop->id) }}">口コミを投稿する</a>
        
                    <!-- すべての口コミを見るリンクをここに移動 -->
                    <a href="{{ route('reviews.index', ['shop' => $shop->id]) }}">すべての口コミを見る</a>
                @else
                    <!-- あなたのレビューの前にリンクを配置 -->
                    <a href="{{ route('reviews.index', ['shop' => $shop->id]) }}">すべての口コミを見る</a>
        
                    <h3>あなたのレビュー</h3>
                    @if($userReview)
                        <li>
                            <div>
                                <p>評価：{{ $userReview->rating }} コメント：{{ $userReview->comment }}</p>
                            </div>
                            <div>
                                @if ($userReview->review_image_url)
                                <img id="currentImage" src="{{ $userReview->review_image_url }}" alt="現在の画像">
                                @endif
                            </div>
                            <div>
                                <a href="{{ route('review.edit', $userReview->id) }}">編集</a>
                                <form action="/review/delete" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                    <button type="submit" onclick="return confirm('レビューを削除しますか？')">削除</button>
                                </form>
                            </div>
                        </li>
                    @endif
                @endif
            @else
                <p>この店舗はまだ訪問していません。</p>
            @endif
        </div>
        
    </div>
    <div class="reservation__form">
        <div class="reservation__form-heading">
            <h2>予約</h2>
        </div>
        {{-- 予約フォームのエラー表示 --}}
        @if ($errors->hasBag('reservation'))
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->reservation->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="reservation__form-container">
            <form class="reservation__form-content" action="{{ route('reservation.process') }}" method="post">
                @csrf
                <div class="reservation__input-group">
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    <input type="date" name="reserve_date" id="reserve_date">
                    <select name="reserve_time" id="reserve_time">
                        <option value="" disabled selected>時間を選択してください</option>
                        @for ($hour = 9; $hour<= 22; $hour++) @foreach (['00', '15' , '30' , '45' ] as $minute) <option
                            value="{{ sprintf('%02d:%02d', $hour, $minute) }}">
                            {{ sprintf('%02d:%02d', $hour, $minute) }}
                            </option>
                            @endforeach
                            @endfor
                    </select>
                    <select name="guest_count" id="guest_count">
                        <option value="" disabled selected>人数を選択してください</option>
                        @for ($i = 1; $i <= 10; $i++) <option value="{{ $i }}">{{ $i }} 人</option>
                            @endfor
                    </select>
                    <div class="confirmation__table">
                        <table>
                            <tr>
                                <th>Shop</th>
                                <td>{{ $shop->shop_name }}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td id="display_date"></td>
                            </tr>
                            <tr>
                                <th>Time</th>
                                <td id="display_time"></td>
                            </tr>
                            <tr>
                                <th>人数</th>
                                <td id="display_guests"></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <button type="submit" class="reserve__button" onclick="return confirm('この内容で予約しますか？')">予約する</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('reserve_date').addEventListener('input', function() {
    document.getElementById('display_date').innerText = this.value;
});

document.getElementById('reserve_time').addEventListener('input', function() {
    document.getElementById('display_time').innerText = this.value;
});

document.getElementById('guest_count').addEventListener('input', function() {
    document.getElementById('display_guests').innerText = this.value + " 人";
});
</script>
@endsection