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
        $shop = Shop::findOrFail($shop_id);
        $review = Review::where('shop_id', $shop_id)
            ->where('user_id', auth()->user()->id)
            ->first();
        $reservationId = Auth::user()->isVisited($shop_id); // 予約IDを取得

        return view('review.create', compact('shop', 'review', 'reservationId'));
    }

    public function index($shop_id)
    {
        $shop = Shop::findOrFail($shop_id);
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
            if ($request->hasFile('image')) {
                $path = config('app.env') === 'production'
                    ? $request->file('image')->store('images/reviews', 's3') // S3
                    : $request->file('image')->store('images/reviews', 'public'); // ローカル

                $reviewData['review_image_url'] = $path; // 相対パスを保存
            }

            Review::create($reviewData);

            // ショップの平均評価を更新
            $shop = Shop::find($request->input('shop_id'));
            if ($shop) {
                $shop->updateShopAverageRating();
            }

            return redirect()->route('detail', ['shop_id' => $request->input('shop_id')])
                ->with('status', 'レビューを投稿しました');
        } else {
            return redirect()->back()->with('status', '既にレビュー済みです');
        }
    }

    public function edit(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }
        return view('review.create', compact('review'));
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

        if ($request->input('delete_image')) {
            $this->deleteImage($review->review_image_url);
            $reviewData['review_image_url'] = null;
        }

        if ($request->hasFile('image')) {
            $path = config('app.env') === 'production'
                ? $request->file('image')->store('images/reviews', 's3') // S3
                : $request->file('image')->store('images/reviews', 'public'); // ローカル

            $this->deleteImage($review->review_image_url);
            $reviewData['review_image_url'] = $path; // 相対パスを保存
        }

        $review->update($reviewData);

        $shop = Shop::find($review->shop_id);
        if ($shop) {
            $shop->updateShopAverageRating();
        }

        return redirect()->route('detail', ['shop_id' => $review->shop_id])
            ->with('status', 'レビューを更新しました');
    }

    private function deleteImage($imageUrl)
    {
        if (!$imageUrl) {
            return;
        }

        if (config('app.env') === 'production') {
            Storage::disk('s3')->delete($imageUrl);
        } else {
            Storage::disk('public')->delete($imageUrl);
        }
    }

    public function destroy(Request $request)
    {
        $review = auth()->user()->hasRole('admin')
            ? Review::where('id', $request->review_id)->where('shop_id', $request->shop_id)->first()
            : auth()->user()->reviews()->where('shop_id', $request->shop_id)->where('id', $request->review_id)->first();

        if ($review) {
            if ($review->review_image_url) {
                $this->deleteImage($review->review_image_url);
            }

            $deleted = $review->delete();

            if ($deleted) {
                $shop = Shop::find($request->shop_id);
                if ($shop) {
                    $shop->updateShopAverageRating();
                }
                return back()->with('success', '投稿を削除しました');
            } else {
                return back()->with('error', '投稿の削除に失敗しました');
            }
        } else {
            return back()->with('error', '削除するレビューが見つかりません');
        }
    }
}
