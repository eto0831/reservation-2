<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Actions\Fortify\PasswordValidationRules;

class RegisterRequest extends FormRequest
{
    use PasswordValidationRules; // トレイトを使用
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
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'password' => $this->passwordRules(),
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前は必須項目です。',
            'name.string' => '名前は文字列でなければなりません。',
            'name.max' => '名前は191文字以内で入力してください。',
            'email.required' => 'メールアドレスは必須項目です。',
            'email.string' => 'メールアドレスは文字列でなければなりません。',
            'email.email' => '有効なメールアドレスを入力してください。',
            'email.max' => 'メールアドレスは191文字以内で入力してください。',
            'email.unique' => 'このメールアドレスは既に登録されています。',
            'password.required' => 'パスワードは必須項目です。',
            'password.min' => 'パスワードは最低8文字必要です。',
            'password.string' => 'パスワードは文字列で入力してください。',
            'password.max' => 'パスワードは191文字以内で入力してください。',
        ];
    }
}
