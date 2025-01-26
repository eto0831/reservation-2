<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    protected $errorBag = 'review';
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
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:400',
            'review_image_url' => 'nullable|image|mimes:jpeg,png',
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '評価を選択してください。',
            'rating.integer' => '評価は整数で入力してください。',
            'rating.between' => '評価は1から5の間で選択してください。',
            'comment.required' => 'コメントを入力してください。',
            'comment.string' => 'コメントは文字で入力してください。',
            'comment.max' => 'コメントは400文字以内で入力してください。',
            'review_image_url.image' => '有効な画像ファイルをアップロードしてください。',
            'review_image_url.mimes' => 'jpeg, png形式のファイルのみアップロード可能です。',
        ];
    }
}
