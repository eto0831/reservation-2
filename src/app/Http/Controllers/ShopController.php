<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use App\Models\Favorite;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::with(['genre', 'area', 'reviews'])
            ->withCount('reviews') // 評価数を取得
            ->paginate(12);

        $areas = Area::all();
        $genres = Genre::all();
        $favorites = auth()->check() ? auth()->user()->favorites()->pluck('shop_id')->toArray() : [];

        return view('index', compact('shops', 'areas', 'genres', 'favorites'));
    }

    public function search(Request $request)
    {
        $sort = $request->input('sort', 'random');

        $query = Shop::with(['genre', 'area', 'reviews'])
            ->withCount('reviews') // 評価数を取得
            ->GenreSearch($request->genre_id)
            ->AreaSearch($request->area_id)
            ->KeywordSearch($request->keyword);

        if ($sort == 'high_rating') {
            $query->orderByRaw('
            CASE WHEN avg_rating IS NULL OR avg_rating = 0 THEN 1 ELSE 0 END ASC,
            avg_rating DESC
        ');
        } elseif ($sort == 'low_rating') {
            $query->orderByRaw('
            CASE WHEN avg_rating IS NULL OR avg_rating = 0 THEN 1 ELSE 0 END ASC,
            avg_rating ASC
        ');
        } else {
            $query->inRandomOrder();
        }

        $shops = $query->paginate(12);

        $areas = Area::all();
        $genres = Genre::all();
        $favorites = auth()->check() ? auth()->user()->favorites()->pluck('shop_id')->toArray() : [];

        return view('index', compact('shops', 'areas', 'genres', 'favorites', 'sort'));
    }



    public function sortByRating() {}


    public function detail(Request $request)
    {
        $shop = Shop::with(['genre', 'area', 'reviews'])
            ->withCount('reviews') // 評価数を取得
            ->findOrFail($request->shop_id);

        $areas = Area::all();
        $genres = Genre::all();

        // 現在のユーザーのレビューを取得
        $userReview = null;
        if (Auth::check()) {
            $userReview = $shop->reviews()
                ->with('user')
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('detail', compact('shop', 'areas', 'genres', 'userReview'));
    }
}
