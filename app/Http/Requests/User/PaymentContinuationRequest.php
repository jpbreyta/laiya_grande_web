<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class PaymentContinuationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'in:gcash,paymaya,bank_transfer'],
            'amount_paid' => ['nullable', 'numeric', 'gt:0'],
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }
}
