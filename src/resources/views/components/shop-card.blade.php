<div class="shop__content">
    <div class="shop-card__container">
        <img src="{{ asset($shop->image_url) }}" alt="{{ $shop->shop_name }}" class="shop__img">
        <div class="shop-articles">
            <h3 class="shop__name">{{ $shop->shop_name }}</h3>
            <p class="shop__categories">
                <span>#{{ $shop->area->area_name }}</span>
                <span>#{{ $shop->genre->genre_name }}</span>
            </p>
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