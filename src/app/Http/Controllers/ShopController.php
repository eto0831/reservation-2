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
            ->get();

        $areas = Area::all();
        $genres = Genre::all();
        $favorites = auth()->check() ? auth()->user()->favorites()->pluck('shop_id')->toArray() : [];

        return view('index', compact('shops', 'areas', 'genres', 'favorites'));
    }

    public function search(Request $request)
    {
        $sort = $request->input('sort'); // 並び替え条件を取得

        $query = Shop::with(['genre', 'area', 'reviews'])
            ->withCount('reviews') // 評価数を取得
            ->GenreSearch($request->genre_id)
            ->AreaSearch($request->area_id)
            ->KeywordSearch($request->keyword);

        if ($sort === 'high_rating') {
            $query->orderByRating('desc'); // 評価が高い順
        } elseif ($sort === 'low_rating') {
            $query->orderByRating('asc'); // 評価が低い順
        } elseif ($sort === 'random') {
            $query->inRandomOrder(); // 毎回異なる順序で取得
        }

        $shops = $query->get(); // ページネーションなしで全件取得

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
