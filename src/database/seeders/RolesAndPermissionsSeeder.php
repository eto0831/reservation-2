<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Shop;

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

        // Giving permissions to roles
        $admin->syncPermissions(['manage owners']);
        $owner->syncPermissions(['manage shops', 'manage reservations']);

        // ユーザーの作成
        $adminUser = User::firstOrCreate(
            ['email' => 'popo1@example.com'],
            [
                'name' => 'popo1',
                'password' => Hash::make('popo1212'),
                'email_verified_at' => now(),
            ]
        );
        $adminUser->assignRole('admin');

        $ownerUser = User::firstOrCreate(
            ['email' => 'popo2@example.com'],
            [
                'name' => 'popo2',
                'password' => Hash::make('popo1212'),
                'email_verified_at' => now(),
            ]
        );
        $ownerUser->assignRole('owner');

        $generalUser = User::firstOrCreate(
            ['email' => 'popo3@example.com'],
            [
                'name' => 'popo3',
                'password' => Hash::make('popo1212'),
                'email_verified_at' => now(),
            ]
        );
        $generalUser->assignRole('user');

        // Ownerテーブルに店舗関連付けを作成
        $shopId = 21;

        if (Shop::find($shopId)) { // 店舗が存在する場合のみ挿入
            DB::table('owners')->updateOrInsert(
                ['user_id' => $ownerUser->id, 'shop_id' => $shopId],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
