<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function userIndex()
    {
        $shops = Shop::all();
        $Genres = Genre::all();
        $Areas = Area::all();
        $users = User::all();

        // オーナーロールを持ち、担当店舗があるユーザーを取得
        $ownersWithShops = User::whereHas('roles', function ($query) {
            $query->where('name', 'owner'); // ロール名が "owner" のユーザー
        })
            ->has('shops') // shopsリレーションが存在するユーザーを取得
            ->with('shops') // shopsリレーションを事前読み込み
            ->get();

        // オーナーロールを持ち、担当店舗がないユーザーを取得
        $ownersWithoutShops = User::whereHas('roles', function ($query) {
            $query->where('name', 'owner'); // ロール名が "owner" のユーザー
        })
            ->doesntHave('shops') // shopsリレーションが存在しないユーザーを取得
            ->get();

        // ユーザーロールを持つユーザーを取得
        $generalUsers = User::whereHas('roles', function ($query) {
            $query->where('name', 'user'); // ロール名が "user" のユーザー
        })->get();

        return view('admin.user_index', compact('shops', 'ownersWithShops', 'ownersWithoutShops', 'Genres', 'Areas', 'users', 'generalUsers'));
    }

    public function createOwner()
    {
        $shops = Shop::all();
        $Genres = Genre::all();
        $Areas = Area::all();
        $users = User::all();
        return view('admin.create_owner', compact('shops', 'Genres', 'Areas', 'users'));
    }

    public function storeOwner(Request $request)
    {
        // バリデーション
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'shop_ids' => 'nullable|array', // 複数店舗の選択を許可
            'shop_ids.*' => 'exists:shops,id', // 店舗IDが有効であることを確認
        ]);

        // ユーザーの作成
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->assignRole('owner');

        // 担当店舗を登録
        if (!empty($request->shop_ids)) {
            $user->shops()->attach($request->shop_ids);
        }

        return redirect('/admin/dashboard')->with('status', '店舗代表者が作成されました。');
    }

    public function editOwner(Request $request)
    {
        // バリデーション
        $request->validate([
            'owner_id' => 'required|exists:users,id', // owner_id が存在することを確認
        ]);

        // オーナー情報を取得
        $owner = User::whereHas('roles', function ($query) {
            $query->where('name', 'owner'); // ロール名が "owner" のユーザー
        })->with('shops')->findOrFail($request->owner_id);

        // すべての店舗を取得
        $shops = Shop::all();

        return view('admin.edit_owner', compact('shops', 'owner'));
    }

    public function updateOwner(Request $request)
    {
        // バリデーション
        $request->validate([
            'owner_id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->owner_id,
            'password' => 'nullable|string|min:8|confirmed',
            'shop_ids' => 'nullable|array', // 複数店舗の選択を許可
            'shop_ids.*' => 'exists:shops,id', // 店舗IDが有効であることを確認
        ]);

        $owner = User::whereHas('roles', function ($query) {
            $query->where('name', 'owner'); // ロール名が "owner" のユーザー
        })->findOrFail($request->owner_id);

        // ユーザー情報の更新
        $owner->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? bcrypt($request->password) : $owner->password,
        ]);

        // 担当店舗の更新
        $owner->shops()->sync($request->shop_ids);

        return redirect()->route('admin.user.index')->with('status', 'オーナー情報を更新しました。');
    }
}
