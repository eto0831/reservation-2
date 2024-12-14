<div class="shop__content">
    <div class="shop-card__container">
        @if ($shop->image_url)
        <img src="{{ asset($shop->image_url) }}" alt="{{ $shop->shop_name }}" class="shop__img">
        @else
        <img src="{{ env('BASE_URL') . '/images/shops/noimage.png' }}" alt="デフォルト画像" class="shop__img">
        @endif
        <div class="shop-articles">
            <h3 class="shop__name">{{ $shop->shop_name }}</h3>
            <p class="shop__categories">
                <span>#{{ $shop->area->area_name }}</span>
                <span>#{{ $shop->genre->genre_name }}</span>
            </p>
            <p>平均評価: {{ number_format($shop->average_rating, 1) }} / 5</p>
            <div class="shop__buttons">
                <a href="/detail/{{ $shop->id }}" class="form__button blue-button">詳しく見る</a>
                <form action="/favorite" method="POST">
                    @csrf
                    @if ($shop->isFavorited)
                    @method('DELETE')
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    <button type="submit" class="heart-icon favorited"></button>
                    @else
                    <input type="hidden" name="shop_id" value="{{ $shop->id }}">
                    <button type="submit" class="heart-icon"></button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>