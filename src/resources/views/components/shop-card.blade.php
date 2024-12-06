<div class="shop__content">
    <img src="{{ asset($shop->image_url) }}" alt="{{ $shop->shop_name }}" class="shop__img">
    <h3>{{ $shop->shop_name }}</h3>
    <p>
        <span>#{{ $shop->area->area_name }}</span>
        <span>#{{ $shop->genre->genre_name }}</span>
    </p>
    <div class="shop__buttons">
        <a href="/detail/{{ $shop->id }}">詳しく見る</a>
        @if ($shop->isFavorited)
        <form action="/favorite" method="post">
            @method('DELETE')
            @csrf
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
            <button type="submit">お気に入りから外す</button>
        </form>
        @else
        <form action="/favorite" method="POST">
            @csrf
            <input type="hidden" name="shop_id" value="{{ $shop->id }}">
            <button type="submit">お気に入り</button>
        </form>
        @endif
    </div>
    <form action="{{ route('shop.edit') }}" method="post" style="display:inline;">
        @csrf
        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
        <button type="submit">編集</button>
    </form>
</div>
