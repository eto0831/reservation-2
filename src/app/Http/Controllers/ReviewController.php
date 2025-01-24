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

    // app/Http/Controllers/ReviewController.php

    public function index($shop_id)
    {
        // ショップ情報を取得
        $shop = Shop::findOrFail($shop_id);

        // 特定のショップのレビューを取得し、関連するユーザー情報もロード
        $reviews = Review::with('user')->where('shop_id', $shop_id)->get();

        // ビューにデータを渡す
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
            $imageUrl = $this->handleImageUpload($request);
            if ($imageUrl) {
                $reviewData['review_image_url'] = $imageUrl;
            }

            // レビューを作成
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
        // ログインユーザーがレビューの作者かどうかを確認
        if ($review->user_id !== auth()->id()) {
            abort(403); // 権限がない場合はアクセスを拒否
        }
        return view('review.create', compact('review'));
    }

    public function update(ReviewRequest $request, Review $review)
    {
        // ログインユーザーがレビューの作者かどうかを確認
        if ($review->user_id !== auth()->id()) {
            abort(403); // 権限がない場合はアクセスを拒否
        }

        // レビューデータを準備
        $reviewData = [
            'rating' => $request->input('rating'),
            'comment' => $request->input('comment'),
        ];

        // 画像の削除処理
        if ($request->input('delete_image')) {
            // 古い画像を削除
            $this->deleteImage($review->review_image_url);
            // データベースの画像URLをnullに設定
            $reviewData['review_image_url'] = null;
        }

        // 新しい画像のアップロード
        $newImageUrl = $this->handleImageUpload($request);
        if ($newImageUrl) {
            // 古い画像を削除（必要に応じて）
            if ($review->review_image_url && !$request->input('delete_image')) {
                $this->deleteImage($review->review_image_url);
            }
            $reviewData['review_image_url'] = $newImageUrl;
        }

        // レビューの更新
        $review->update($reviewData);

        // ショップの平均評価を更新
        $shop = Shop::find($review->shop_id);
        if ($shop) {
            $shop->updateShopAverageRating();
        }

        return redirect()->route('detail', ['shop_id' => $review->shop_id])
            ->with('status', 'レビューを更新しました');
    }

    // 画像削除の共通メソッド
    private function deleteImage($imageUrl)
    {
        if (!$imageUrl) {
            return;
        }

        if (config('app.env') === 'production') {
            // S3から画像を削除
            $path = parse_url($imageUrl, PHP_URL_PATH);
            $path = ltrim($path, '/'); // 先頭のスラッシュを削除
            Storage::disk('s3')->delete($path);
        } else {
            // ローカルから画像を削除
            $relativePath = str_replace(env('BASE_URL') . '/', '', $imageUrl);
            Storage::delete('public/' . $relativePath);
        }
    }

    private function handleImageUpload(Request $request)
    {
        if ($request->hasFile('image')) {
            if (config('app.env') === 'production') {
                // S3にアップロード
                $path = Storage::disk('s3')->put('images/reviews', $request->file('image'));
                return Storage::disk('s3')->url($path);
            } else {
                // ローカルに保存
                $imagePath = $request->file('image')->store('public/images/reviews');
                $relativePath = str_replace('public/', '', $imagePath);

                // BASE_URLに/を追加
                return env('BASE_URL') . '/' . $relativePath;
            }
        }
        return null;
    }

    public function destroy(Request $request)
    {
        // 削除するレビューを取得
        $review = auth()->user()->reviews()->where('shop_id', $request->shop_id)->first();

        if ($review) {
            // 画像が存在する場合は削除
            if ($review->review_image_url) {
                $this->deleteImage($review->review_image_url);
            }

            // レビューを削除
            $deleted = $review->delete();

            if ($deleted) {
                $shop = Shop::find($request->shop_id);
                if ($shop) {
                    $shop->updateShopAverageRating(); // 平均評価を更新
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
