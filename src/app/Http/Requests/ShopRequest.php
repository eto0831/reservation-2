<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShopRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'shop_name'   => 'required|string|max:40',
            'area_id'     => 'required',
            'genre_id'    => 'required',
            'description' => 'nullable|string|max:191',
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'アップロードされたファイルは画像である必要があります。',
            'image.mimes' => '画像は jpeg, png, jpg, gif のいずれかの形式でアップロードしてください。',
            'image.max' => '画像ファイルのサイズは2MB以下にしてください。',
            'shop_name.required' => '店舗名を入力してください。',
            'shop_name.string' => '店舗名は文字列で入力してください。',
            'shop_name.max' => '店舗名は40文字以内で入力してください。',
            'area_id.required' => 'エリアを選択してください。',
            'genre_id.required' => 'ジャンルを選択してください。',
            'description.string' => '説明は文字列で入力してください。',
            'description.max' => '説明は191文字以内で入力してください。',
        ];
    }
}
