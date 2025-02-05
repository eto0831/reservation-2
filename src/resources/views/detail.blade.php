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
                    <a class="shop__heading-link" href="/">&lt;</a>
                    <h2 class="shop__heading-title">{{ $shop->shop_name }}</h2>
                </div>
                <div class="shop__img-container">
                    @if ($shop->image_url)
                        <img src="{{ Storage::url($shop->image_url) }}" alt="{{ $shop->shop_name }}" class="shop__img">
                    @else
                        <img src="{{ Storage::url('images/shops/noimage.png') }}" alt="デフォルト画像" class="shop__img">
                    @endif
                </div>

                <div class="shop__details">
                    <p class="shop__details-content">
                        <span>#{{ $shop->area->area_name }}</span>
                        <span>#{{ $shop->genre->genre_name }}</span>
                    </p>
                    <p class="shop__details-rating" title="☆は平均評価、()内は評価数です。">{{ number_format($shop->avg_rating, 2) }} (
                        {{ $shop->reviews_count }} ) </p>
                </div>
                <div class="shop__description">
                    <p>{{ $shop->description }}</p>
                </div>
            </div>
            <div class="review__wrap">
                @if (Auth::check() && ($reservationId = Auth::user()->isVisited($shop->id)))
                    {{-- レビュー済みかどうかをチェック --}}
                    @if (!$shop->hasReviewed(Auth::user()->id))
                        @unless (Auth::user()->hasRole(['admin', 'owner']))
                            <div class="review-form__link">
                                <a class="review-form__link-item" href="{{ route('review', $shop->id) }}">口コミを投稿する</a>
                            </div>
                        @endunless
                        <!-- 全ての口コミ情報リンクをここに移動 -->
                        <div class="review-list__link">
                            <a class="review-list__link-item"
                                href="{{ route('reviews.index', ['shop' => $shop->id]) }}">全ての口コミ情報</a>
                        </div>
                    @else
                        <!-- 自分のレビューの前にリンクを配置 -->
                        <div class="review-list__link">
                            <a class="review-list__link-item"
                                href="{{ route('reviews.index', ['shop' => $shop->id]) }}">全ての口コミ情報</a>
                        </div>
                        {{-- 自分のレビュー --}}
                        @if ($userReview)
                            <div class="my-review__container">
                                <div class="my-review__header">
                                    <div class="my-review__links">
                                        <a class="my-review__links-item"
                                            href="{{ route('review.edit', $userReview->id) }}">口コミを編集</a>
                                        <form class="my-review__links-item" action="{{ route('review.delete') }}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                                            <input type="hidden" name="review_id" value="{{ $userReview->id }}">
                                            <button class="my-review__links-button-submit" type="submit"
                                                onclick="return confirm('レビューを削除しますか？')">口コミを削除</button>
                                        </form>
                                    </div>
                                </div>
                                <div class="my-review__content">
                                    <div class="my-review__content-rating">
                                        <p class="my-review__stars" data-rating="{{ $userReview->rating }}"></p>
                                    </div>
                                    <div class="my-review__content-comment">
                                        <p>{{ $userReview->comment }}</p>
                                    </div>
                                    <div class="my-review__img-container">
                                        @if ($userReview && $userReview->review_image_url)
                                            <img class="my-review__img-full" id="currentImage"
                                                src="{{ Storage::url($userReview->review_image_url) }}" alt="現在の画像">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @else
                    <div class="review-list__link">
                        <a class="review-list__link-item"
                            href="{{ route('reviews.index', ['shop' => $shop->id]) }}">全ての口コミ情報</a>
                    </div>
                @endif
            </div>
        </div>
        <div class="reservation-form__wrapper">
            <div class="reservation-form__heading">
                <h2 class="reservation-form__heading-title">予約</h2>
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
            <div class="reservation-form__content">
                <form class="reservation-form" action="{{ route('reservation.process') }}" method="post">
                    @csrf
                    <div class="reservation-form__form-group">
                        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                        <input class="reservation-form__input" type="date" name="reserve_date" id="reserve_date">
                        <select class="reservation-form__select time" name="reserve_time" id="reserve_time">
                            <option value="" disabled selected>時間を選択してください</option>
                            @for ($hour = 9; $hour <= 22; $hour++)
                                @foreach (['00', '15', '30', '45'] as $minute)
                                    <option value="{{ sprintf('%02d:%02d', $hour, $minute) }}">
                                        {{ sprintf('%02d:%02d', $hour, $minute) }}
                                    </option>
                                @endforeach
                            @endfor
                        </select>
                        <select class="reservation-form__select number" name="guest_count" id="guest_count">
                            <option value="" disabled selected>人数を選択してください</option>
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }} 人</option>
                            @endfor
                        </select>
                        <div class="confirmation-table__wrapper">
                            <table class="confirmation-table">
                                <tr class="confirmation-table__row">
                                    <th class="confirmation-table__label">Shop</th>
                                    <td class="confirmation-table__data">{{ $shop->shop_name }}</td>
                                </tr>
                                <tr class="confirmation-table__row">
                                    <th class="confirmation-table__label">Date</th>
                                    <td class="confirmation-table__data" id="display_date"></td>
                                </tr>
                                <tr class="confirmation-table__row">
                                    <th class="confirmation-table__label">Time</th>
                                    <td class="confirmation-table__data" id="display_time"></td>
                                </tr>
                                <tr class="confirmation-table__row">
                                    <th class="confirmation-table__label">人数</th>
                                    <td class="confirmation-table__data" id="display_guests"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <button type="submit" class="reserve__button" onclick="return confirm('この内容で予約しますか？')">予約する</button>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ asset('js/detail.js') }}"></script>

@endsection
