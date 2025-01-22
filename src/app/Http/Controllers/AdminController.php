<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Shop;
use App\Models\Area;
use App\Models\Genre;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateOwnerRequest;
use App\Http\Requests\UpdateOwnerRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



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

    public function storeOwner(CreateOwnerRequest $request)
    {
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

    public function editOwner($owner_id)
    {
        // オーナー情報を取得
        $owner = User::whereHas('roles', function ($query) {
            $query->where('name', 'owner'); // ロール名が "owner" のユーザー
        })->with('shops')->findOrFail($owner_id);

        // すべての店舗を取得
        $shops = Shop::all();

        return view('admin.edit_owner', compact('shops', 'owner'));
    }


    public function updateOwner(UpdateOwnerRequest $request)
    {
        $owner = User::whereHas('roles', function ($query) {
            $query->where('name', 'owner');
        })->findOrFail($request->owner_id);

        // ユーザー情報の更新
        $owner->name = $request->name;
        $owner->email = $request->email;
        if ($request->password) {
            $owner->password = bcrypt($request->password);
        }
        $owner->save();

        return redirect()->route('admin.user.index')->with('status', 'オーナー情報を更新しました。');
    }


    public function attachShop(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:users,id',
            'shop_id'  => 'required|exists:shops,id',
        ]);

        $owner = User::findOrFail($request->owner_id);

        if (!$owner->hasRole('owner')) {
            return redirect()->route('admin.owner.edit', ['owner_id' => $owner->id])->withErrors(['owner_id' => '指定されたユーザーはオーナーではありません。']);
        }

        // 既に担当していないか確認
        if (!$owner->shops->contains($request->shop_id)) {
            $owner->shops()->attach($request->shop_id);
            return redirect()->route('admin.owner.edit', ['owner_id' => $owner->id])->with('status', '店舗を追加しました。');
        } else {
            return redirect()->route('admin.owner.edit', ['owner_id' => $owner->id])->withErrors(['shop_id' => 'この店舗は既に担当しています。']);
        }
    }

    public function detachShop(Request $request)
    {
        $request->validate([
            'owner_id' => 'required|exists:users,id',
            'shop_id'  => 'required|exists:shops,id',
        ]);

        $owner = User::findOrFail($request->owner_id);

        if (!$owner->hasRole('owner')) {
            return redirect()->route('admin.owner.edit', ['owner_id' => $owner->id])->withErrors(['owner_id' => '指定されたユーザーはオーナーではありません。']);
        }

        // 担当店舗の解除
        $owner->shops()->detach($request->shop_id);

        return redirect()->route('admin.owner.edit', ['owner_id' => $owner->id])->with('status', '担当店舗を解除しました。');
    }

    public function csvIndex()
    {
        return view('admin.import_csv');
    }


    public function storeCsv(Request $request)
    {
        // ファイルのバリデーション
        $validator = Validator::make($request->all(), [
            'csv' => 'required|mimes:csv,txt'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        // アップロードされたCSVファイルを取得
        $path = $request->file('csv')->getRealPath();

        // CSVファイルを読み込む
        $file = fopen($path, 'r');

        // デリミタを指定してヘッダーを取得
        $header = fgetcsv($file, 0, ',');

        // 最初のヘッダーからBOMを除去
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);

        // 期待するヘッダー
        $expectedHeaders = ['shop_name', 'area_name', 'genre_name', 'description', 'image_url'];

        // ヘッダーの確認
        if ($header !== $expectedHeaders) {
            //dd($header); // ヘッダーの内容を確認する場合
            return redirect()->back()->withErrors(['CSVのヘッダーが期待する形式と一致しません。']);
        }

        $errors = [];
        $lineNumber = 1;

        while (($row = fgetcsv($file, 0, ',')) !== false) {
            $lineNumber++;

            // CSVの行を連想配列にマッピング
            $data = array_combine($header, $row);

            // 各行のバリデーション
            $rowValidator = Validator::make($data, [
                'shop_name'   => 'required|string|max:50',
                'area_name'   => 'required|in:東京,大阪,福岡',
                'genre_name'  => 'required|in:寿司,焼肉,イタリアン,居酒屋,ラーメン',
                'description' => 'required|string|max:400',
                'image_url'   => 'required|url',
            ]);

            if ($rowValidator->fails()) {
                $errors[$lineNumber] = $rowValidator->errors()->all();
                continue;
            }

            // 画像URLの拡張子を確認
            $extension = Str::lower(pathinfo($data['image_url'], PATHINFO_EXTENSION));
            if (!in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $errors[$lineNumber][] = '画像はJPEGまたはPNG形式である必要があります。';
                continue;
            }

            // エリアとジャンルのIDを取得
            $area = Area::where('area_name', $data['area_name'])->first();
            $genre = Genre::where('genre_name', $data['genre_name'])->first();

            if (!$area || !$genre) {
                $errors[$lineNumber][] = 'エリアまたはジャンルが正しくありません。';
                continue;
            }

            // Shopレコードを作成
            Shop::create([
                'shop_name'   => $data['shop_name'],
                'area_id'     => $area->id,
                'genre_id'    => $genre->id,
                'description' => $data['description'],
                'image_url'   => $data['image_url'],
            ]);
        }

        fclose($file);

        if (!empty($errors)) {
            // 行ごとのエラーを返す
            return redirect()->back()->withErrors($errors);
        }

        return redirect()->back()->with('success', 'CSVのインポートが完了しました。');
    }
}
