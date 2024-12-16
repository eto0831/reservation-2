@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/stores/verify.css') }}">
@endsection

@section('content')
<div class="verify-reservation">
    <h2>予約照合</h2>
    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif
    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif
    <div class="reservation-details">
        <p>店舗: {{ $reservation->shop->shop_name }}</p>
        <p>日時: {{ $reservation->reserve_date }} {{ $reservation->reserve_time }}</p>
        <p>人数: {{ $reservation->guest_count }}人</p>
        <form action="{{ route('reservation.updateIsVisited', $reservation->id) }}" method="post">
            @csrf
            <button type="submit">来店確認</button>
        </form>
    </div>
</div>
@endsection
