@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>

<div class="detail__content">
    <div class="detail__wrap">
        <h1>店舗詳細</h1>
        {{-- レビューフォームのエラー表示 --}}
        @if ($errors->hasBag('review'))
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->review->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <ul>
            <li>
                <h2>{{ $shop->shop_name }}</h2>
                <p>ジャンル: {{ $shop->genre->genre_name }}</p>
                <p>エリア: {{ $shop->area->area_name }}</p>
                <p>説明: {{ $shop->description }}</p>
                @if ($shop->image_url)
                <img src="{{ asset($shop->image_url) }}" alt="{{ $shop->shop_name }}" class="shop__img">
                @else
                <img src="{{ env('BASE_URL') . '/images/shops/noimage.png' }}" alt="デフォルト画像" class="shop__img">
                @endif

            </li>
        </ul>
        {{-- detail.blade.php --}}

        @if (Auth::check() && $reservationId = Auth::user()->isVisited($shop->id))
        <p>この店舗は訪問済みです。</p>

        {{-- レビュー済みかどうかをチェック --}}
        @if (!$shop->hasReviewed(Auth::user()->id))
        <form action="/review" method="post">
            @csrf
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
            <input type="hidden" name="user_id">
            <input type="hidden" name="reservation_id" value="{{ $reservationId }}">
            <select name="rating" id="rating">
                @for ($i = 1; $i <=5; $i++) <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
            </select>
            <input type="text" name="comment" value="" placeholder="レビューは191文字以内の上、必ずご入力ください。">
            <button type="submit" onclick="return confirm('この内容でレビューを投稿しますか？')">投稿</button>
        </form>
        @endif

        @else
        <p>この店舗はまだ訪問していません。</p>
        @endif
        <h4>レビュー一覧</h4>
        @foreach($reviews as $review)
        <li>
            評価：{{ $review->rating }} コメント：{{ $review->comment }} by {{$review->user->name}}
            @if (Auth::check() && $review->user_id === Auth::user()->id)
            <div>
                <a href="{{ route('review.edit', $review->id) }}">編集</a>
                <form action="/review/delete" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    <button type="submit" onclick="return confirm('レビューを削除しますか？')">削除</button>
                </form>
            </div>
            @endif
        </li>
        @endforeach
    </div>
    <div class="reservation__form">
        <h1>予約</h1>
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

        <form action="{{ route('reservation.process') }}" method="post">
            @csrf
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
            <button type="submit" class="reserve__button" onclick="return confirm('この内容で予約しますか？')">予約する</button>
        </form>
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
    document.getElementById('display_guests').innerText = this.value;
});
</script>
@endsection