<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $favorite = [
            'user_id' => auth()->user()->id, // ログイン中のユーザーID
            'shop_id' => $request->shop_id, // リクエストから取得した店舗ID
        ];

        Favorite::create($favorite);

        return back();
    }

    public function destroy(Request $request)
    {
        auth()->user()->favorites()->where('shop_id', $request->shop_id)->delete();

        return back()->with('error', 'お気に入りの削除に失敗しました');
    }
}
