<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class ReservationRequest extends FormRequest
{
    protected $errorBag = 'reservation';

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'shop_id' => 'required|exists:shops,id',
            'reserve_date' => 'required|date',
            'reserve_time' => 'required',
            'guest_count' => 'required|integer|min:1|max:10',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateDateTime($validator);
        });
    }

    private function validateDateTime($validator)
    {
        $reserveDate = $this->input('reserve_date');
        $reserveTime = $this->input('reserve_time');

        if ($reserveDate && $reserveTime) {
            // 日付と時間を組み合わせて現在と比較
            $combinedDateTime = Carbon::parse("$reserveDate $reserveTime");

            if ($combinedDateTime->isPast()) {
                // 1つのエラーとして追加
                $validator->errors()->add('reservation_datetime', '予約日時は現在以降を指定してください。');
            }
        }
    }


    public function messages()
    {
        return [
            'shop_id.required' => '店舗が選択されていません。',
            'shop_id.exists' => '選択された店舗が存在しません。',
            'reserve_date.required' => '予約日を選択してください。',
            'reserve_date.date' => '有効な日付を入力してください。',
            'reserve_time.required' => '予約時間を選択してください。',
            'guest_count.required' => '人数を入力してください。',
            'guest_count.integer' => '有効な人数を入力してください。',
            'guest_count.min' => '人数は1人以上で指定してください。',
            'guest_count.max' => '人数は10人以下で指定してください。',
        ];
    }
}
