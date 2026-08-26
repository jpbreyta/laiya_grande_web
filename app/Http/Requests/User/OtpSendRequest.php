<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class OtpSendRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc', 'max:255'],
            'first_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['nullable', 'string', 'max:100'],
            'phone' => ['nullable', 'regex:/^(?:\+?63|0)9\d{9}$/'],
            'otp_method' => ['nullable', 'in:email,sms'],
        ];
    }
}
