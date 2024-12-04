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

    public function createOwners()
    {
        $shops = Shop::all();
        $Genres = Genre::all();
        $Areas = Area::all();
        $owners = Owner::all();
        $users = User::all();
        return view('admin.create_owner', compact('shops', 'owners','Genres','Areas','users'));
    }

    public function storeOwners(Request $request)
{
    $owner = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);
    $owner->assignRole('owner');

    // 中間テーブルにレコードを追加
    $owner->shops()->attach($request->shop_id, [
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect('/admin/dashboard')->with('status', '店舗代表者が作成されました。');
}


}
