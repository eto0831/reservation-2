<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Requests\ReviewRequest;
use App\Models\Shop;


class ReviewController extends Controller
{
    public function review()
    {
        return view('review');
    }

    public function store(ReviewRequest $request)
    {
        $review = Review::where('shop_id', $request->input('shop_id'))
            ->where('user_id', auth()->user()->id)
            ->first();

        if (!$review) {
            Review::create([
                'shop_id' => $request->input('shop_id'),
                'user_id' => auth()->user()->id,
                'reservation_id' => $request->input('reservation_id'),
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
            ]);

            // ショップモデルのインスタンスを取得して平均評価を更新
            $shop = Shop::find($request->input('shop_id'));
            if ($shop) {
                $shop->updateShopAverageRating();
            }

            return redirect()->back()->with('status', 'レビューの作成に成功しました');
        } else {
            return redirect()->back()->with('status', '既にレビュー済みです');
        }
    }

    public function destroy(Request $request)
    {
        $deleted = auth()->user()->reviews()->where('shop_id', $request->shop_id)->delete();

        if ($deleted) {
            $shop = Shop::find($request->shop_id); // リクエストから shop_id を取得
            if ($shop) {
                $shop->updateShopAverageRating(); // 平均評価を更新
            }
            return back()->with('success', '投稿を削除しました');
        } else {
            return back()->with('error', '投稿の削除に失敗しました');
        }
    }

    public function edit(Review $review)
    {
        // ログインユーザーがレビューの作者かどうかを確認
        if ($review->user_id !== auth()->id()) {
            abort(403); // 権限がない場合はアクセスを拒否
        }
        return view('review.edit', compact('review'));
    }

    public function update(ReviewRequest $request, Review $review)
    {
        // ログインユーザーがレビューの作者かどうかを確認
        if ($review->user_id !== auth()->id()) {
            abort(403); // 権限がない場合はアクセスを拒否
        }
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // ショップモデルのインスタンスを取得して平均評価を更新
        $shop = Shop::find($review->shop_id); // Review 経由で shop_id を取得
        if ($shop) {
            $shop->updateShopAverageRating(); // 引数なしで呼び出し
        }

        return redirect()->route('detail', ['shop_id' => $review->shop_id])
            ->with('status', 'レビューを更新しました');
    }
}
