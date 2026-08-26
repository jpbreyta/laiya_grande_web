<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class OtpVerifyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['nullable', 'email:rfc', 'max:255'],
            'reservation_code' => ['nullable', 'string', 'max:50'],
            'booking_number' => ['nullable', 'string', 'max:50'],
            'otp' => ['required', 'digits:6'],
        ];
    }
}
