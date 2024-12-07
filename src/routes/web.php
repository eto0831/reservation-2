<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\AdminNotificationController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/welcome', function () {
    return view('welcome');
});
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [ShopController::class, 'index']);
    Route::get('/detail/{shop_id}', [ShopController::class, 'detail'])->name('detail');;
    Route::post('/reservation', [ReservationController::class, 'store']);
    Route::delete('/reservation', [ReservationController::class, 'destroy']);
    Route::match(['get', 'post'], '/search', [ShopController::class, 'search']);
    Route::post('/favorite', [FavoriteController::class, 'store']);
    Route::delete('/favorite', [FavoriteController::class, 'destroy']);
    Route::get('/mypage', [AuthController::class, 'index']);
    // 予約編集のルート
    Route::post('/reservation/edit', [ReservationController::class, 'edit'])->name('reservation.edit');
    // 予約更新のルート
    Route::patch('/reservation/update', [ReservationController::class, 'update'])->name('reservation.update');

    Route::get('/reservation/scan', [ReservationController::class, 'scan'])->name('reservation.scan');
    Route::get('/reservation/verify/{id?}', [ReservationController::class, 'verify'])->name('reservation.verify');
    Route::post('/reservation/verify/{id}', [ReservationController::class, 'updateIsVisited'])->name('reservation.updateIsVisited');

    Route::post('/review', [ReviewController::class, 'store']);
    Route::delete('/review/delete', [ReviewController::class, 'destroy']);
    Route::get('/review/edit/{review}', [ReviewController::class, 'edit'])->name('review.edit');
    Route::put('/review/update/{review}', [ReviewController::class, 'update'])->name('review.update');

    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment.process');


    Route::post('/reservation/process', [ReservationController::class, 'process'])->name('reservation.process');
});

// web.php

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/user/index', [AdminController::class, 'userIndex'])->name('admin.user.index');
    Route::get('/admin/owner/create', [AdminController::class, 'createOwner'])->name('admin.owner.create');
    Route::post('/admin/owner/store', [AdminController::class, 'storeOwner'])->name('admin.owner.store');

    // オーナー編集画面の表示（GETメソッド、owner_idをURLパラメータとして渡す）
    Route::get('/admin/owner/edit/{owner_id}', [AdminController::class, 'editOwner'])->name('admin.owner.edit');

    // オーナー情報の更新（PATCHメソッド）
    Route::patch('/admin/owner/update', [AdminController::class, 'updateOwner'])->name('admin.owner.update');

    // 担当店舗の追加と解除のルートを追加
    Route::post('/admin/owner/attach-shop', [AdminController::class, 'attachShop'])->name('admin.owner.attachShop');
    Route::post('/admin/owner/detach-shop', [AdminController::class, 'detachShop'])->name('admin.owner.detachShop');
    Route::get('/email-notification', [AdminNotificationController::class, 'showForm'])->name('admin.emailNotification');
    Route::post('/email-notification', [AdminNotificationController::class, 'sendNotification'])->name('admin.sendNotification');
});


Route::group(['middleware' => ['auth', 'verified', 'role:owner', 'can:manage shops', 'can:manage reservations']], function () {
    Route::get('/owner/dashboard', [OwnerController::class, 'dashboard']);
    Route::get('/owner/shops', [OwnerController::class, 'shops'])->name('owner.shops');
    Route::get('/owner/shop/create', [OwnerController::class, 'createShop'])->name('owner.shop.create');
    Route::post('/owner/shop/store', [OwnerController::class, 'storeShop'])->name('owner.shop.store');
    Route::post('/owner/shop/edit', [OwnerController::class, 'editShop'])->name('owner.shop.edit');
    Route::patch('/owner/shop/update', [OwnerController::class, 'updateShop'])->name('owner.shop.update');
    Route::delete('/owner/shop/delete', [OwnerController::class, 'destroyShop'])->name('owner.shop.destroy');

    Route::get('/owner/reservations', [OwnerController::class, 'reservations'])->name('owner.reservations');
    Route::post('/owner/reservation/edit', [OwnerController::class, 'editReservation'])->name('owner.reservation.edit');
    Route::patch('/owner/reservation/update', [OwnerController::class, 'updateReservation'])->name('owner.reservation.update');
    Route::delete('/owner/reservation/destroy', [OwnerController::class, 'destroyReservation'])->name('owner.reservation.destroy');
});
