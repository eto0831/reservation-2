<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CsvImportRequest extends FormRequest
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
            'csv' => 'required|mimes:csv,txt'
        ];
    }

    public function validateCsvData(array $data, int $lineNumber): array
    {
        $validator = \Validator::make($data, [
            'shop_name'   => 'required|string|max:50',
            'area_name'   => 'required|in:東京都,大阪府,福岡県',
            'genre_name'  => 'required|in:寿司,焼肉,イタリアン,居酒屋,ラーメン',
            'description' => 'required|string|max:400',
            'image_url'   => 'required|string',
        ], [
            'shop_name.required'   => "行{$lineNumber}: 店舗名は必須です。",
            'shop_name.max'        => "行{$lineNumber}: 店舗名は最大50文字以内で入力してください。",
            'area_name.required'   => "行{$lineNumber}: エリア名は必須です。",
            'area_name.in'         => "行{$lineNumber}: エリア名は「東京都」「大阪府」「福岡県」のいずれかを指定してください。",
            'genre_name.required'  => "行{$lineNumber}: ジャンルは必須です。",
            'genre_name.in'        => "行{$lineNumber}: ジャンルは「寿司」「焼肉」「イタリアン」「居酒屋」「ラーメン」のいずれかを指定してください。",
            'description.required' => "行{$lineNumber}: 店舗概要は必須です。",
            'description.max'      => "行{$lineNumber}: 店舗概要は最大400文字以内で入力してください。",
            'image_url.required'   => "行{$lineNumber}: 画像URLは必須です。",
        ]);

        return $validator->fails() ? $validator->errors()->all() : [];
    }

    public function messages(): array
    {
        return [
            'csv.required' => 'CSVファイルを選択してください。',
            'csv.mimes' => 'CSVファイルの形式が正しくありません。',
        ];
    }
}
