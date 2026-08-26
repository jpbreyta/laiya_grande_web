<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CartAddRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'room_rate_id' => ['nullable', 'integer', 'exists:room_rates,id'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:20'],
        ];
    }
}
