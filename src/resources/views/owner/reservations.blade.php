@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/reservations.css') }}">
@endsection

@section('content')
<div class="reservation__alert">
    {{ session('status') }}
</div>

<div class="container">
    <h2>店舗別 予約一覧</h2>
    @foreach ($shops as $shop)
    <div class="shop-section">
        <h3>{{ $shop->shop_name }}</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>予約者名</th>
                    <th>予約日</th>
                    <th>予約時間</th>
                    <th>人数</th>
                    <th>アクション</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shop->reservations as $reservation)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $reservation->user->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->reserve_date)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reservation->reserve_time)->format('H:i') }}</td>
                    <td>{{ $reservation->guest_count }}人</td>
                    <td>
                        <!-- 更新フォーム -->
                        <form action="{{ route('owner.reservation.edit', $reservation) }}" method="get">
                            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                            <button type="submit">編集</button>
                        </form>
                        <!-- 削除フォーム -->
                        <form action="{{ route('owner.reservation.destroy') }}" method="post">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                            <button type="submit" onclick="return confirm('本当に削除しますか？')">削除</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">予約はありません。</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endforeach
    <div class="back__button">
        <a href="/owner/dashboard">戻る</a>
    </div>
</div>
@endsection
