<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;
use App\Models\Reservation;
use App\Models\Shop;

class PaymentController extends Controller
{
    public function index()
{
    // セッションから予約情報を取得
    $reservationData = session('reservation_data');
    if (!$reservationData) {
        return redirect()->back()->with('error', '予約情報が見つかりません。');
    }

    $shop = Shop::find($reservationData['shop_id']);
    return view('payment.index', compact('reservationData', 'shop'));
}

public function processPayment(Request $request)
{
    // APIキーの設定
    Stripe::setApiKey(config('cashier.secret'));

    // セッションから予約情報を取得
    $reservationData = session('reservation_data');
    if (!$reservationData) {
        return back()->with('error', '予約情報が見つかりません。');
    }

    $request->validate([
        'payment_method' => 'required|string',
    ]);

    // コース情報は決済時のみ使用
    $coursePrices = [
        'matsu' => 10000,
        'take'  => 8000,
        'ume'   => 5000,
    ];

    $courseKey = $request->input('course');
    if (!array_key_exists($courseKey, $coursePrices)) {
        return back()->with('error', '不正なコースが選択されました。');
    }

    $coursePrice = $coursePrices[$courseKey];
    $guestCount = $reservationData['guest_count'];
    $amount = $coursePrice * $guestCount;

    try {
        $user = $request->user();
        $user->createOrGetStripeCustomer();
        $user->addPaymentMethod($request->payment_method);

        // 決済の実行
        PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'jpy',
            'customer' => $user->stripe_id,
            'payment_method' => $request->payment_method,
            'off_session' => true,
            'confirm' => true,
        ]);

        // 予約情報をデータベースに保存（コース情報は保存しない）
        Reservation::create(array_merge($reservationData, [
            'user_id' => $user->id,
        ]));

        // セッションから予約情報を削除
        $request->session()->forget('reservation_data');

        return redirect('/thanks')->with('status', '予約と決済が完了しました！');
    } catch (ApiErrorException $e) {
        return back()->with('error', '決済に失敗しました：' . $e->getMessage());
    }
}

public function success()
{
    return view('/thanks');
}

}
