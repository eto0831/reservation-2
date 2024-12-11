<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminNotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // このリクエストを許可するかどうかを定義
        return auth()->user()->hasRole('admin'); // 管理者ロールのみ許可
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'target' => 'required|in:all,admin,owner,user', // 許可されたターゲット値のみ
            'subject' => 'required|string|max:191', // 件名は191文字以内
            'message' => 'required|string|max:5000', // メッセージは最大5000文字
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'target.required' => '送信先を選択してください。',
            'target.in' => '選択された送信先は無効です。',
            'subject.required' => 'メールの件名を入力してください。',
            'subject.string' => '件名は文字列で入力してください。',
            'subject.max' => '件名は191文字以内で入力してください。',
            'message.required' => 'メールの内容を入力してください。',
            'message.string' => 'メールの内容は文字列で入力してください。',
            'message.max' => 'メールの内容は5000文字以内で入力してください。',
        ];
    }
}
