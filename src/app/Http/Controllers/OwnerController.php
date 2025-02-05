<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shop;
use App\Models\Reservation;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReservationRequest;
use App\Http\Requests\ShopRequest;

class OwnerController extends Controller
{
    public function dashboard()
    {
        return view('owner.dashboard');
    }

    public function shops()
    {
        // 現在のオーナーが担当するすべての店舗を取得
        $shops = Auth::user()->shops()->with(['area', 'genre'])->get();

        // 店舗が存在しない場合のチェック
        if ($shops->isEmpty()) {
            abort(404, '担当しているショップが見つかりません');
        }

        return view('owner.shops', compact('shops'));
    }

    public function createShop()
    {
        $areas = Area::all();
        $genres = Genre::all();
        return view('owner.create_shop', compact('areas', 'genres'));
    }

    public function storeShop(ShopRequest $request)
    {

        $shopData = [
            'shop_name'   => $request->shop_name,
            'area_id'     => $request->area_id,
            'genre_id'    => $request->genre_id,
            'description' => $request->description,
        ];

        if ($request->hasFile('image')) {
            if (config('app.env') === 'production') {
                // S3に保存
                $path = $request->file('image')->store('images/shops', 's3'); // S3にアップロード
            } else {
                // ローカルに保存
                $path = $request->file('image')->store('images/shops', 'public'); // ローカルに保存
            }

            // 相対パスを保存（どちらの場合でも対応可能）
            $shopData['image_url'] = $path;
        }

        $shop = Shop::create($shopData);

        // Ownersテーブルを確認し、null の行があれば更新する
        $existingOwner = DB::table('owners')
            ->where('user_id', Auth::id())
            ->whereNull('shop_id')
            ->first();

        if ($existingOwner) {
            // null の行を更新
            DB::table('owners')
                ->where('id', $existingOwner->id)
                ->update([
                    'shop_id'    => $shop->id,
                    'updated_at' => now(),
                ]);
        } else {
            // 新しい行を作成
            DB::table('owners')->insert([
                'user_id'    => Auth::id(),
                'shop_id'    => $shop->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect('/owner/shops')->with('status', '店舗情報を作成しました');
    }


    public function editShop($shop_id)
    {
        // 店舗情報を取得
        $shop = Shop::findOrFail($shop_id);

        // 認可チェック (Policy)
        $this->authorize('update', $shop);

        $areas = Area::all();
        $genres = Genre::all();

        return view('owner.edit_shop', compact('shop', 'areas', 'genres'));
    }


    public function updateShop(ShopRequest $request)
    {
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
            if (config('app.env') === 'production') {
                // S3に保存
                $path = $request->file('image')->store('images/shops', 's3'); // S3にアップロード
            } else {
                // ローカルに保存
                $path = $request->file('image')->store('images/shops', 'public'); // ローカルに保存
            }

            // 相対パスを保存（どちらの場合でも対応可能）
            $shopData['image_url'] = $path;
        }


        Shop::find($request->input('shop_id'))->update($shopData);

        return redirect('/owner/shops')->with('status', '店舗情報を変更しました');
    }

    public function destroyShop(Request $request)
    {
        try {
            Shop::where('id', $request->shop_id)->delete();
            return redirect('/owner/shops')->with('status', '店舗情報を削除しました');
        } catch (\Exception $e) {
            return redirect('/owner/shops')->with('status', '店舗情報の削除に失敗しました');
        }
    }

    public function reservations()
    {
        // 現在のオーナーが担当するすべての店舗の予約情報を取得
        $shops = Auth::user()
            ->shops()
            ->with(['reservations' => function ($query) {
                $query->orderByRaw('CASE WHEN reserve_date >= ? THEN 0 ELSE 1 END, reserve_date ASC, reserve_time ASC', [now()->toDateString()]);
            }, 'reservations.user'])
            ->get();

        return view('owner.reservations', compact('shops'));
    }


    // OwnerController
    public function destroyReservation(Request $request)
    {
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
        ]);

        $reservation = Reservation::findOrFail($request->reservation_id);

        // 担当店舗の予約のみ削除可能
        if (!Auth::user()->shops->contains($reservation->shop_id)) {
            abort(403, 'この予約を削除する権限がありません');
        }

        $reservation->delete();

        return redirect('/owner/reservations')->with('status', '予約を削除しました');
    }

    public function editReservation($reservation_id)
    {
        $reservation = Reservation::findOrFail($reservation_id);

        // 担当店舗の予約のみ編集可能
        if (!Auth::user()->shops->contains($reservation->shop_id)) {
            abort(403, 'この予約を編集する権限がありません');
        }

        $shop = $reservation->shop;

        return view('owner.edit_reservation', compact('reservation', 'shop'));
    }

    public function updateReservation(ReservationRequest $request)
    {
        $reservation = Reservation::findOrFail($request->reservation_id);

        // 担当店舗の予約のみ更新可能
        if (!Auth::user()->shops->contains($reservation->shop_id)) {
            abort(403, 'この予約を更新する権限がありません');
        }

        $reservation->update([
            'reserve_date' => $request->reserve_date,
            'reserve_time' => $request->reserve_time,
            'guest_count' => $request->guest_count,
        ]);

        return redirect('/owner/reservations')->with('status', '予約を変更しました');
    }
}
