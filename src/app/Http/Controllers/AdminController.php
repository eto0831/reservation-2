<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Shop;
use App\Models\Owner;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $owners = Owner::all();
        $users = User::all();
        return view('admin.create_owner', compact('shops', 'owners', 'Genres', 'Areas', 'users'));
    }

    public function storeOwner(Request $request)
    {
        // バリデーション
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'shop_id'  => 'nullable|exists:shops,id',
        ]);

        // ユーザーの作成
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $user->assignRole('owner');

        // Ownersテーブルにレコードを直接追加
        Owner::create([
            'user_id' => $user->id,
            'shop_id' => $request->shop_id, // nullの場合もそのまま保存
        ]);

        return redirect('/admin/dashboard')->with('status', '店舗代表者が作成されました。');
    }

    public function editOwner()
    {
        $shops = Shop::all();
        $Genres = Genre::all();
        $Areas = Area::all();
        $owners = Owner::all();
        $users = User::all();
        return view('admin.edit_owner', compact('shops', 'owners', 'Genres', 'Areas', 'users'));
    }
}
