<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Carbon;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Roles
        $admin = Role::create(['name' => 'admin']);
        $owner = Role::create(['name' => 'owner']);
        $user = Role::create(['name' => 'user']);

        // Permissions
        Permission::create(['name' => 'manage owners']); // システム管理、admin
        Permission::create(['name' => 'manage shops']); // 店舗情報管理、owner
        Permission::create(['name' => 'manage reservations']); // 予約情報管理、owner

        // Giving permissions to roles
        $admin->givePermissionTo(['manage owners']);
        $owner->givePermissionTo(['manage shops', 'manage reservations']);

        // ユーザーの作成
        $adminUser = User::create([
            'name' => 'popo1',
            'email' => 'popo1@example.com',
            'password' => Hash::make('popo1212'),
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole('admin');

        $ownerUser = User::create([
            'name' => 'popo2',
            'email' => 'popo2@example.com',
            'password' => Hash::make('popo1212'),
            'email_verified_at' => now(),
        ]);
        $ownerUser->assignRole('owner');

        $generalUser = User::create([
            'name' => 'popo3',
            'email' => 'popo3@example.com',
            'password' => Hash::make('popo1212'),
            'email_verified_at' => now(),
        ]);
        $generalUser->assignRole('user');
    }
}
