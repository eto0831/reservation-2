<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Area;
use App\Models\Genre;

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

        Shop::create($shopData);

        return redirect('/owner/dashboard');
    }

    public function edit($id)
    {
        $shop = Shop::find($id);
        $areas = Area::all();
        $genres = Genre::all();

        return view('owner.edit_shop', compact('shop', 'areas', 'genres'));
    }

    public function update(Request $request)
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
            return redirect('/owner/dashboard')->with('success', '店舗情報を削除しました');
        } catch (\Exception $e) {
            return redirect('/owner/dashboard')->with('error', '店舗情報の削除に失敗しました: ' . $e->getMessage());
        }
    }
}
