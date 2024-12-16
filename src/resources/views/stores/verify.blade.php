@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/stores/verify.css') }}">
@endsection

@section('content')
<div class="verify-reservation">
    <h2>予約照合</h2>

    <!-- 成功メッセージ -->
    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <!-- エラーメッセージ -->
    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <!-- 予約内容テーブル -->
    <table class="reservation-table">
        <tr>
            <th>店舗</th>
            <td>{{ $reservation->shop->shop_name }}</td>
        </tr>
        <tr>
            <th>日付</th>
            <td>{{ $reservation->reserve_date }}</td>
        </tr>
        <tr>
            <th>時間</th>
            <td>{{ $reservation->reserve_time }}</td>
        </tr>
        <tr>
            <th>人数</th>
            <td>{{ $reservation->guest_count }} 人</td>
        </tr>
    </table>

    <!-- ボタンエリア -->
    <div class="button-group">
        <!-- 戻るボタン -->
        <a href="/mypage" class="btn-back">戻る</a>

        <!-- 来店確認ボタン -->
        <form action="{{ route('reservation.updateIsVisited', $reservation->id) }}" method="post">
            @csrf
            <button type="submit" class="form__button-submit">来店確認</button>
        </form>
    </div>
</div>
@endsection
