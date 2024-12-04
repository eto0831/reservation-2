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

        // オーナーロールを持つユーザーを取得
        $owners = User::whereHas('roles', function ($query) {
            $query->where('name', 'owner'); // ロール名が "owner" のユーザー
        })->get();
        return view('admin.user_index', compact('shops', 'owners', 'Genres', 'Areas', 'users'));
    }

    public function createOwners()
    {
        $shops = Shop::all();
        $Genres = Genre::all();
        $Areas = Area::all();
        $owners = Owner::all();
        $users = User::all();
        return view('admin.create_owner', compact('shops', 'owners', 'Genres', 'Areas', 'users'));
    }

    public function storeOwners(Request $request)
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
}
