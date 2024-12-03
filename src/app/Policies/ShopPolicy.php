<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Shop;
use App\Models\Owner;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function update(User $user, Shop $shop)
    {
        // ownersテーブルで、user_id と shop_id の関連付けがあるかを確認
    return $shop->owners()->where('user_id', $user->id)->exists();
    }
}
