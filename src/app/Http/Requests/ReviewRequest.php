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
            'comment' => 'required|string|max:191',
        ];
    }

    public function messages()
    {
        return [
            'rating.required' => '店舗が選択されていません。',
            'shop_id.exists' => '選択された店舗が存在しません。',
            'comment.required' => 'コメントを入力してください。',
            'comment.string'=>'コメントは文字で入力してください。',
            'comment.max' => 'コメントは191文字以内で入力してください。',
        ];
    }
}
