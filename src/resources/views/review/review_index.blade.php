@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/review/review_index.css') }}">
@endsection

@section('content')
    <h2>{{ $shop->shop_name }}のレビュー一覧</h2>

    @if ($reviews->isEmpty())
        <p>レビューはまだありません</p>
    @else
        <ul>
            @foreach($reviews as $review)
                <li>
                    <div>
                        <p>
                            レーティング：{{ $review->rating }} / 5<br>
                            コメント：{{ $review->comment }}<br>
                            投稿者：{{ $review->user->name }}
                        </p>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    <!-- 必要に応じて詳細ページへのリンクを追加 -->
    <a href="{{ route('detail', ['shop_id' => $shop->id]) }}">店舗詳細に戻る</a>
@endsection
