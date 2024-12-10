<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Http\Requests\RegisterRequest;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input) // 引数を RegisterRequest に変更
    {
        // validate メソッドを削除
        //RegisterRequest を使用してバリデーションを実行
        $request = app(RegisterRequest::class);
        $input = $request->validated(); // バリデーション済みの入力データを取得

        return User::create([
            'name' => $request->name, // $request から入力値を取得
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    }
}
