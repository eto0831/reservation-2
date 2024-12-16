@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/edit_reservation.css') }}">
@endsection

@section('content')
<div class="reservation__alert">
    {{ session('status') }}
</div>

<div class="reservation__form">
    <h2>予約</h2>
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

    <form action="{{ route('owner.reservation.update') }}" method="post">
        @csrf
        @method('PATCH')
        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
        <input type="hidden" name="shop_id" value="{{ $shop->id }}">

        <label for="reserve_date">日付</label>
        <input type="date" name="reserve_date" id="reserve_date" value="{{ $reservation->reserve_date }}">

        <label for="reserve_time">時間</label>
        <select name="reserve_time" id="reserve_time">
            <option value="" disabled>時間を選択してください</option>
            @for ($hour = 9; $hour<= 22; $hour++)
                @foreach (['00', '15', '30', '45'] as $minute)
                <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}"
                    @if (\Carbon\Carbon::createFromFormat('H:i:s', $reservation->reserve_time)->format('H:i') == sprintf('%02d:%02d', $hour, $minute)) selected @endif>
                    {{ sprintf('%02d:%02d', $hour, $minute) }}
                </option>
                @endforeach
            @endfor
        </select>

        <label for="guest_count">人数</label>
        <select name="guest_count" id="guest_count">
            <option value="" disabled>人数を選択してください</option>
            @for ($i = 1; $i <= 10; $i++)
            <option value="{{ $i }}" @if ($reservation->guest_count == $i) selected @endif>{{ $i }}人</option>
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
                    <td id="display_date">{{ $reservation->reserve_date }}</td>
                </tr>
                <tr>
                    <th>Time</th>
                    <td id="display_time">{{ \Carbon\Carbon::createFromFormat('H:i:s', $reservation->reserve_time)->format('H:i') }}</td>
                </tr>
                <tr>
                    <th>人数</th>
                    <td id="display_guests">{{ $reservation->guest_count }}人</td>
                </tr>
            </table>
        </div>

        <div class="form__button-group">
            <a href="/owner/dashboard" class="form__button-back">戻る</a>
            <button class="form__button-submit" type="submit" onclick="return confirm('この内容で確定しますか？')">変更を確定</button>
        </div>
    </form>
</div>

<script>
    // 日付のリアルタイム表示
    document.getElementById('reserve_date').addEventListener('input', function () {
        document.getElementById('display_date').innerText = this.value;
    });

    // 時間のリアルタイム表示
    document.getElementById('reserve_time').addEventListener('change', function () {
        document.getElementById('display_time').innerText = this.value;
    });

    // 人数のリアルタイム表示
    document.getElementById('guest_count').addEventListener('change', function () {
        document.getElementById('display_guests').innerText = this.value + "人";
    });
</script>
@endsection
