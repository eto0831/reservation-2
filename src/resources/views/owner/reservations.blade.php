@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/owner/reservations.css') }}">
@endsection

@section('content')
<div class="container">
    <h1>店舗別 予約一覧</h1>
    @foreach ($shops as $shop)
    <div class="shop-section">
        <h2>{{ $shop->shop_name }}</h2>
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
                        <form action="{{ route('reservation.update', $reservation->id) }}" method="post" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit">更新</button>
                        </form>
                        <!-- 削除フォーム -->
                        <form action="{{ route('reservation.destroy', $reservation->id) }}" method="post" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">削除</button>
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
</div>
@endsection
