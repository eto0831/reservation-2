<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Genre;
use App\Models\Area;
use App\Models\Review;
use App\Models\Reservation;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class AuthController extends Controller
{
    public function index()
    {
        $reservations = auth()->user()->reservations()->with('shop')->get();
        $areas = Area::all();
        $genres = Genre::all();
        $favorites = auth()->user()->favorites()
            ->with(['shop.area', 'shop.genre']) // 関連データを取得
            ->with(['shop' => function ($query) {
                $query->withCount('reviews'); // reviews_countを追加
            }])->get();

        return view('mypage.index', compact('reservations', 'areas', 'genres', 'favorites'));
    }
}
