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
    Route::get('/review/{review}/edit', [ReviewController::class, 'edit'])->name('review.edit');
    Route::put('/review/update/{review}', [ReviewController::class, 'update'])->name('review.update');

    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('/payment', [PaymentController::class, 'processPayment'])->name('payment.process');


    Route::post('/reservation/process', [ReservationController::class, 'process'])->name('reservation.process');
});

Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/user/index', [AdminController::class, 'userIndex']);
    Route::get('/admin/create/owner', [AdminController::class, 'createOwners']);
    Route::post('/admin/create/owner', [AdminController::class, 'storeOwners']);
});

Route::group(['middleware' => ['auth', 'verified', 'role:owner', 'can:manage shops', 'can:manage reservations']], function () {
    Route::get('/owner/dashboard', [OwnerController::class, 'dashboard']);
    Route::get('/shop/create', [OwnerController::class, 'create'])->name('shop.create');
    Route::post('/shop/create', [OwnerController::class, 'store'])->name('shop.store');
    Route::get('/shop/edit/{id}', [OwnerController::class, 'edit'])->name('shop.edit');
    Route::patch('/shop/update/{id}', [OwnerController::class, 'update'])->name('shop.update');
    Route::delete('/shop/delete/{id}', [OwnerController::class, 'destroy'])->name('shop.destroy');

    Route::get('/owner/reservations', [OwnerController::class, 'reservations'])->name('owner.reservations');
    Route::delete('/owner/reservation', [ReservationController::class, 'destroy'])->name('reservation.destroy');
    Route::post('/owner/reservation/edit', [ReservationController::class, 'edit'])->name('owner.reservation.edit');
    Route::patch('/owner/reservation/update', [ReservationController::class, 'update'])->name('owner.reservation.update');
});
