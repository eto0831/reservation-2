<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Requests\ReviewRequest;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function review($shop_id)
    {
        $shop = Shop::withCount('reviews')->findOrFail($shop_id);
        $review = Review::where('shop_id', $shop_id)
            ->where('user_id', auth()->user()->id)
            ->first();
        $reservationId = Auth::user()->isVisited($shop_id); // 予約IDを取得

        return view('review.create', compact('shop', 'review', 'reservationId'));
    }

    public function index($shop_id)
    {
        $shop = Shop::withCount('reviews')->findOrFail($shop_id);
        $reviews = Review::with('user')->where('shop_id', $shop_id)->get();
        return view('review.review_index', compact('reviews', 'shop'));
    }

    public function store(ReviewRequest $request)
    {
        $review = Review::where('shop_id', $request->input('shop_id'))
            ->where('user_id', auth()->user()->id)
            ->first();

        if (!$review) {
            $reviewData = [
                'shop_id' => $request->input('shop_id'),
                'user_id' => auth()->user()->id,
                'reservation_id' => $request->input('reservation_id'),
                'rating' => $request->input('rating'),
                'comment' => $request->input('comment'),
            ];

            // 画像のアップロード処理
            if ($request->hasFile('review_image_url')) {
                $path = config('app.env') === 'production'
                    ? $request->file('review_image_url')->store('images/reviews', 's3') // S3
                    : $request->file('review_image_url')->store('images/reviews', 'public'); // ローカル

                $reviewData['review_image_url'] = $path; // 相対パスを保存
            }


            Review::create($reviewData);

            // ショップの平均評価を更新
            $shop = Shop::find($request->input('shop_id'));
            if ($shop) {
                $shop->updateShopAverageRating();
            }

            return redirect()->route('detail', ['shop_id' => $request->input('shop_id')])
                ->with('status', '口コミを投稿しました');
        } else {
            return redirect()->back()->with('status', '既に口コミ済みです');
        }
    }

    public function edit(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }
        $shop = Shop::withCount('reviews')->find($review->shop_id);
        return view('review.create', compact('review', 'shop'));
    }




    public function update(ReviewRequest $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $reviewData = [
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ];

        // 古い画像を削除する処理を追加
        if ($request->hasFile('review_image_url')) {
            if ($review->review_image_url) {
                $this->deleteImage($review->review_image_url); // 古い画像を削除
            }

            $path = config('app.env') === 'production'
                ? $request->file('review_image_url')->store('images/reviews', 's3') // S3
                : $request->file('review_image_url')->store('images/reviews', 'public'); // ローカル

            $reviewData['review_image_url'] = $path; // 新しい画像を保存
        } elseif ($request->input('delete_image')) {
            // 明示的に削除チェックボックスがオンの場合も古い画像を削除
            $this->deleteImage($review->review_image_url);
            $reviewData['review_image_url'] = null;
        }

        $review->update($reviewData);

        $shop = Shop::find($review->shop_id);
        if ($shop) {
            $shop->updateShopAverageRating();
        }

        return redirect()->route('detail', ['shop_id' => $review->shop_id])
            ->with('status', '口コミを更新しました');
    }


    private function deleteImage($imageUrl)
    {
        if (!$imageUrl) {
            return;
        }

        if (config('app.env') === 'production') {
            // S3の場合はそのまま削除
            $relativePath = ltrim(parse_url($imageUrl, PHP_URL_PATH), '/');
            Storage::disk('s3')->delete($relativePath);
        } else {
            // ローカルの場合は 'public/' を補完して削除
            Storage::disk('public')->delete($imageUrl);
        }
    }


    public function destroy(Request $request)
    {
        // 必須項目のバリデーション
        $request->validate([
            'shop_id' => 'required|exists:shops,id',
            'review_id' => 'required|exists:reviews,id',
        ]);

        // 口コミを取得
        $reviewQuery = Review::where('id', $request->review_id)->where('shop_id', $request->shop_id);

        if (!auth()->user()->hasRole('admin')) {
            $reviewQuery->where('user_id', auth()->id());
        }

        $review = $reviewQuery->first();

        if (!$review) {
            return back()->with('error', '削除する口コミが見つかりません');
        }

        // 画像を削除
        if ($review->review_image_url) {
            $this->deleteImage($review->review_image_url);
        }

        // 口コミを削除
        $review->delete();

        // ショップの平均評価を更新
        $shop = Shop::find($request->shop_id);
        if ($shop) {
            $shop->updateShopAverageRating();
        }

        return back()->with('success', '投稿を削除しました');
    }
}
