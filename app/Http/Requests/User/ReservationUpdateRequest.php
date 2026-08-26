<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ReservationUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['required', 'regex:/^(?:\+?63|0)9\d{9}$/'],
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'room_rate_id' => ['required', 'integer', 'exists:room_rates,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'number_of_guests' => ['required', 'integer', 'min:1', 'max:100'],
            'special_request' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
