<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Shop;
use App\Models\Review;
use App\Models\Reservation;
use App\Models\Favorite;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Roles
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $user = Role::firstOrCreate(['name' => 'user']);

        // Permissions
        Permission::firstOrCreate(['name' => 'manage owners']);
        Permission::firstOrCreate(['name' => 'manage shops']);
        Permission::firstOrCreate(['name' => 'manage reservations']);

        // Assign permissions to roles
        $admin->syncPermissions(['manage owners']);
        $owner->syncPermissions(['manage shops', 'manage reservations']);

        // ユーザー作成
        $adminUser = User::firstOrCreate(['email' => 'popo1@example.com'], [
            'name' => 'popo1',
            'password' => Hash::make('popo1212'),
            'email_verified_at' => now(),
        ])->assignRole('admin');

        $ownerUser = User::firstOrCreate(['email' => 'popo2@example.com'], [
            'name' => 'popo2',
            'password' => Hash::make('popo1212'),
            'email_verified_at' => now(),
        ])->assignRole('owner');

        $generalUser = User::firstOrCreate(['email' => 'popo3@example.com'], [
            'name' => 'popo3',
            'password' => Hash::make('popo1212'),
            'email_verified_at' => now(),
        ])->assignRole('user');

        // 新しいユーザー追加
        $popo5 = User::firstOrCreate(['email' => 'popo5@example.com'], [
            'name' => 'popo5',
            'password' => Hash::make('popo1212'),
            'email_verified_at' => now(),
        ])->assignRole('user');

        $popo7 = User::firstOrCreate(['email' => 'popo7@example.com'], [
            'name' => 'popo7',
            'password' => Hash::make('popo1212'),
            'email_verified_at' => now(),
        ])->assignRole('user');

        $popo8 = User::firstOrCreate(['email' => 'popo8@example.com'], [
            'name' => 'popo8',
            'password' => Hash::make('popo1212'),
            'email_verified_at' => now(),
        ])->assignRole('user');

        // popo5とpopo7が22店舗全てにレビューと評価を書く
        $shops = Shop::all();
        foreach ($shops as $shop) {
            foreach ([$popo5, $popo7] as $user) {
                $reservation = Reservation::firstOrCreate([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                ], [
                    'reserve_date' => now()->subDays(rand(1, 30))->toDateString(),
                    'reserve_time' => '19:00',
                    'guest_count' => rand(1, 5),
                    'is_visited' => 1,
                ]);

                Review::firstOrCreate([
                    'user_id' => $user->id,
                    'shop_id' => $shop->id,
                    'reservation_id' => $reservation->id,
                ], [
                    'rating' => rand(1, 5),
                    'comment' => '良いお店でした！',
                ]);
            }
            // ショップごとに avg_rating を更新
            $shop->updateShopAverageRating();
        }

        // popo8が22店舗に1日ずつ予約を入れる
        $date = now(); // 初期日付を現在の日付に設定
        foreach ($shops as $shop) {
            Reservation::firstOrCreate([
                'user_id' => $popo8->id,
                'shop_id' => $shop->id,
            ], [
                'reserve_date' => $date->toDateString(),
                'reserve_time' => '18:00',
                'guest_count' => rand(1, 5),
                'is_visited' => 0,
            ]);
            $date->addDay(); // 次の日にインクリメント
        }

        // popo2に店舗ID 21と22の担当権限を与える
        DB::table('owners')->updateOrInsert(
            ['user_id' => $ownerUser->id, 'shop_id' => 21],
            ['created_at' => now(), 'updated_at' => now()]
        );
        DB::table('owners')->updateOrInsert(
            ['user_id' => $ownerUser->id, 'shop_id' => 22],
            ['created_at' => now(), 'updated_at' => now()]
        );

        // ユーザーがランダムにお気に入りを追加
        $users = [$generalUser, $popo5, $popo7, $popo8];
        $shopIds = Shop::pluck('id')->toArray();

        foreach ($users as $user) {
            $randomShopIds = collect($shopIds)->random(rand(5, 10));
            foreach ($randomShopIds as $shopId) {
                Favorite::firstOrCreate([
                    'user_id' => $user->id,
                    'shop_id' => $shopId,
                ]);
            }
        }
    }
}
