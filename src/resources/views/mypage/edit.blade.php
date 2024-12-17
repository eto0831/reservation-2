@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/edit.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>

<div class="detail__content">
    <!-- 店舗詳細部分 -->
    <div class="detail__wrap">
        <div class="shop__info">
            <h2>{{ $shop->shop_name }}</h2>
            <p>ジャンル: {{ $shop->genre->genre_name }}</p>
            <p>エリア: {{ $shop->area->area_name }}</p>
            <p>平均評価: {{ number_format($shop->average_rating, 1) }} / 5</p>
            <p>説明: {{ $shop->description }}</p>
            <img src="{{ asset($shop->image_url) }}" alt="{{ $shop->shop_name }}" class="shop__img">
        </div>
    </div>

    <!-- 予約フォーム部分 -->
    <div class="reservation__form">
        <div class="reservation__form-heading">
            <h2>予約の編集</h2>
        </div>

        @if ($errors->hasBag('reservation'))
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->reservation->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('reservation.update') }}" method="post">
            @csrf
            @method('PATCH')

            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">

            <input type="date" name="reserve_date" id="reserve_date" value="{{ $reservation->reserve_date }}" class="reservation__input">
            <select name="reserve_time" id="reserve_time" class="reservation__input">
                <option value="" disabled>時間を選択してください</option>
                @for ($hour = 9; $hour <= 22; $hour++)
                @foreach (['00', '15', '30', '45'] as $minute)
                <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}"
                    @if (\Carbon\Carbon::createFromFormat('H:i:s', $reservation->reserve_time)->format('H:i') == sprintf('%02d:%02d', $hour, $minute)) selected @endif>
                    {{ sprintf('%02d:%02d', $hour, $minute) }}
                </option>
                @endforeach
                @endfor
            </select>

            <select name="guest_count" id="guest_count" class="reservation__input">
                <option value="" disabled>人数を選択してください</option>
                @for ($i = 1; $i <= 10; $i++)
                <option value="{{ $i }}" @if ($reservation->guest_count == $i) selected @endif>
                    {{ $i }}人</option>
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
            <button type="submit" class="reserve__button" onclick="return confirm('この内容で確定しますか？')">変更を確定する</button>
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
        document.getElementById('display_guests').innerText = this.value + '人';
    });

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('display_date').innerText = document.getElementById('reserve_date').value;
        document.getElementById('display_time').innerText = document.getElementById('reserve_time').value;
        document.getElementById('display_guests').innerText = document.getElementById('guest_count').value + '人';
    });
</script>
@endsection
