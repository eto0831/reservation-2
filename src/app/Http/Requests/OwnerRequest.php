<?php

namespace App\Http\Requests;

use App\Http\Requests\RegisterRequest;

class OwnerRequest extends RegisterRequest
{
    public function rules()
    {
        // 親クラス(RegisterRequest)のルールを取得
        $parentRules = parent::rules();

        // OwnerRequest 独自のルールを追加
        return array_merge($parentRules, [
            'shop_ids' => 'nullable|array',
            'shop_ids.*' => 'exists:shops,id',
        ]);
    }

    public function messages()
    {
        // 親クラス(RegisterRequest)のメッセージを取得
        $parentMessages = parent::messages();

        // OwnerRequest 独自のメッセージを追加
        return array_merge($parentMessages, [
            'shop_ids.*.exists' => '選択された店舗が存在しません。',
        ]);
    }
}
