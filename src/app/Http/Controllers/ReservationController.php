<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Genre;
use App\Models\Area;
use App\Models\User;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ReservationRequest;

class ReservationController extends Controller
{

    // 予約情報作成の予備段階メソッド（PaymentControllerにセッションを送る）
    public function process(ReservationRequest $request)
    {

        // 予約情報をセッションに保存
        $reservationData = $request->only(['shop_id', 'reserve_date', 'reserve_time', 'guest_count']);
        session(['reservation_data' => $reservationData]);

        // 決済画面にリダイレクト
        return redirect()->route('payment.index');
    }

    public function destroy(Request $request)
    {
        // バリデーション: reservation_id が送信されていることを確認
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
        ]);

        // 該当予約を取得
        $reservation = Reservation::findOrFail($request->reservation_id);

        // 自分の予約のみ削除可能
        if (Auth::id() !== $reservation->user_id) {
            abort(403, 'この予約を削除する権限がありません');
        }

        $reservation->delete();

        return redirect('/mypage')->with('status', '予約を削除しました');
    }

    public function edit($reservation_id)
    {
        // 該当予約を取得
        $reservation = Reservation::findOrFail($reservation_id);

        if (Auth::id() !== $reservation->user_id) {
            abort(403, 'この予約を編集する権限がありません');
        }

        $shop = $reservation->shop;

        return view('mypage.edit', compact('reservation', 'shop'));
    }

    public function update(ReservationRequest $request)
    {
        // IDの有無を確認
        $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
        ]);

        // 該当予約を取得
        $reservation = Reservation::findOrFail($request->reservation_id);

        if (Auth::id() !== $reservation->user_id) {
            abort(403, 'この予約を更新する権限がありません');
        }

        return redirect('/mypage')->with('status', '予約を変更しました');
    }


    public function scan()
    {
        return view('stores.scan');
    }

    public function verify($id = null)
    {
        if (!$id || !is_numeric($id)) {
            return redirect()->route('reservation.scan')->with('error', '無効なQRコードが読み取られました。');
        }

        $reservation = Reservation::find($id);

        if ($reservation) {
            return view('stores.verify', compact('reservation'));
        } else {
            return redirect()->route('reservation.scan')->with('error', '予約が見つかりませんでした。');
        }
    }

    public function updateIsVisited(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        if ($reservation) {
            $reservation->is_visited = true;
            $reservation->save();
            return redirect()->back()->with('success', '来店が確認されました');
        } else {
            return redirect()->back()->with('error', '予約が見つかりませんでした');
        }
    }

}
