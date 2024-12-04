<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard()
    {
        return view('owner.dashboard');
    }

    public function create()
    {
        $areas = Area::all();
        $genres = Genre::all();
        return view('owner.create_shop', compact('areas', 'genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像のバリデーションルールを追加
        ]);

        $shopData = [
            'shop_name' => $request->shop_name,
            'area_id' => $request->area_id,
            'genre_id' => $request->genre_id,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/images/shops');
            $shopData['image_url'] = str_replace('public/', 'storage/', $imagePath); // パスを公開用に変換
        }

        $shop = Shop::create($shopData);

        // オーナー情報をownersテーブルに保存
        DB::table('owners')->insert([
            'user_id' => Auth::id(), // 現在ログインしているユーザーのID
            'shop_id' => $shop->id, // 作成された店舗のID
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect('/owner/dashboard')->with('status', '店舗情報を作成しました');
    }

    public function edit($id)
    {
        $shop = Shop::find($id);

        // nullチェック
        if (!$shop) {
            abort(404, 'ショップが見つかりません');
        }
        // 認可チェックsrc/app/Providers/AuthServiceProvider.phpに登録済みのポリシー
        $this->authorize('update', $shop);
        $areas = Area::all();
        $genres = Genre::all();

        return view('owner.edit_shop', compact('shop', 'areas', 'genres'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 画像のバリデーションルールを追加
        ]);

        $shop = Shop::find($request->input('shop_id'));

        // 認可チェックsrc/app/Providers/AuthServiceProvider.phpに登録済みのポリシー
        $this->authorize('update', $shop);

        $shopData = [
            'shop_name' => $request->shop_name,
            'area_id' => $request->area_id,
            'genre_id' => $request->genre_id,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('public/images/shops'); // ディレクトリを変更
            $shopData['image_url'] = str_replace('public/', 'storage/', $imagePath); // パスを公開用に変換
        }

        Shop::find($request->input('shop_id'))->update($shopData);

        return redirect('/owner/dashboard')->with('status', '店舗情報を変更しました');
    }

    public function destroy(Request $request)
    {
        try {
            Shop::where('id', $request->shop_id)->delete();
            return redirect('/owner/dashboard')->with('status', '店舗情報を削除しました');
        } catch (\Exception $e) {
            return redirect('/owner/dashboard')->with('status', '店舗情報の削除に失敗しました');
        }
    }

    public function index()
    {
        $reservations = shop()->reservations()->with('user')->get();
        $areas = Area::all();
        $genres = Genre::all();
        return view('owner.index', compact('reservations', 'areas', 'genres'));
    }
}
