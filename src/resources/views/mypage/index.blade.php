@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage/index.css') }}">
<link rel="stylesheet" href="{{ asset('css/components/shop-card.css') }}">
@endsection

@section('content')
<div class="attendance__alert">
    {{ session('status') }}
</div>
<div class="user__name">
    <h2>{{ auth()->user()->name }}さん</h2>
</div>

<div class="mypage__content">
    <div class="reservation__container">
        <h3>予約一覧</h3>
        <div class="reservations__wrap">
            @foreach ($reservations as $reservation)
            <div class="reservation__contents">
                <div class="reservation__header">
                    <h4>予約 {{ $loop->iteration }}</h4>
                    <div class="reservation__menus">
                        <button title="QRを表示" class="icon-button qr-button" popovertarget="Modal{{ $reservation->id }}" popovertargetaction="show"></button>
                        <div popover id="Modal{{ $reservation->id }}">
                            <div class="qr-code">
                                {!! QrCode::size(150)->generate(url('/reservation/verify/' . $reservation->id)) !!}
                            </div>
                            <button popovertarget="Modal{{ $reservation->id }}" popovertargetaction="hidden">閉じる</button>
                        </div>
                        <form action="/reservation/edit/{{$reservation->id}}" class="reservation__edit" method="get">
                            <button class="icon-button edit-button" type="submit" title="予定を編集"></button>
                        </form>
                        <form action="/reservation" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="reservation_id" value="{{ $reservation->id}}">
                            <button class="icon-button delete-button" type="submit" onclick="return confirm('予約を削除しますか？')" title="予定を削除"></button>
                        </form>
                    </div>
                </div>
                <h4>{{ $reservation->shop->shop_name }}</h4>
                <p>Date: {{ \Carbon\Carbon::parse($reservation->reserve_date)->locale('ja')->isoFormat('YYYY-MM-DD (dd)') }} </p>
                <p>Time: {{ \Carbon\Carbon::parse($reservation->reserve_time)->format('H:i') }}</p>
                </p>
                <p>Number: {{ $reservation->guest_count }}人</p>
            </div>
            @endforeach
        </div>
    </div>
    <div class="favorites__container">
        <h3>お気に入り一覧</h3>
        <div class="favorites__wrap">
            @foreach($favorites as $favorite)
            @include('components.shop-card', ['shop' => $favorite->shop])
            @endforeach
        </div>
    </div>
</div>
@endsection