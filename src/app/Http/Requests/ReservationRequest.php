<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class ReservationRequest extends FormRequest
{
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
            'reserve_date' => 'required|date',
            'reserve_time' => 'required|after_or_equal_now',
            'guest_count' => 'required|integer|min:0|max:11',
        ];
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $this->validateDateTime($validator);
        });
    }

    /**
     * Validate that the combined date and time is in the future.
     */
    private function validateDateTime($validator)
    {
        $reserveDate = $this->input('reserve_date');
        $reserveTime = $this->input('reserve_time');

        if ($reserveDate && $reserveTime) {
            $combinedDateTime = Carbon::parse("$reserveDate $reserveTime");

            if ($combinedDateTime->isPast()) {
                $validator->errors()->add('reserve_date', '本日以降の日時を選択してください。');
                $validator->errors()->add('reserve_time', '本日以降の日時を選択してください。');
            }
        }
    }

    public function messages()
    {
        return [
            'reserve_date' => '日付を選択してください',
            'reserve_time' => '時間を選択してください',
            'reserve_time.after_or_equal_now' => '予約日時は現在より後にしてください',
            'guest_count' => '1～10人までの人数を選択してください',
        ];
    }
}
