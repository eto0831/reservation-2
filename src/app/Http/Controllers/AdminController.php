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

    public function create()
    {
        $shops = Shop::all();
        $owners = Owner::all();
        return view('admin.create_owner', compact('areas', 'genres'));
    }
    
    public function storeOwners(Request $request)
    {
        $owner = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $owner->assignRole('owner');

        return redirect()->back()->with('success', '店舗代表者が作成されました。');
    }
}
