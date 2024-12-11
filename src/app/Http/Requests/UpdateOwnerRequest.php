<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateOwnerRequest extends FormRequest
{
    public function rules()
    {
        // リクエストからオーナーIDを取得
        $ownerId = $this->input('owner_id');

        return [
            'name' => [
                'required',
                'string',
                'max:191',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:191',
                'unique:users,email,' . $ownerId, // オーナーIDを使用して現在のメールを除外
            ],
            'password' => [
                'nullable',
                'string',
                'max:191',
                Password::default(), // デフォルトのパスワードルールを適用
            ],
            'shop_ids' => 'nullable|array',
            'shop_ids.*' => 'exists:shops,id',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前を入力してください。',
            'name.string' => '名前は文字列で入力してください。',
            'name.max' => '名前は191文字以内で入力してください。',
            'email.required' => 'メールアドレスを入力してください。',
            'email.string' => 'メールアドレスは文字列で入力してください。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.unique' => '指定のメールアドレスは既に使用されています。',
            'password.string' => 'パスワードは文字列で入力してください。',
            'password.max' => 'パスワードは191文字以内で入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'shop_ids.*.exists' => '選択された店舗が存在しません。',
        ];
    }
}
