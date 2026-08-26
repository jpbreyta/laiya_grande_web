<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class BookingSubmissionRequest extends FormRequest
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
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'guests' => ['required', 'integer', 'min:1', 'max:100'],
            'special_request' => ['nullable', 'string', 'max:2000'],
            'special_requests' => ['nullable', 'string', 'max:2000'],
            'payment_method' => ['required', 'in:gcash,paymaya,bank_transfer'],
            'payment_proof' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120', 'required_without:payment_proof_temp'],
            'payment_proof_temp' => ['nullable', 'string', 'max:500', 'required_without:payment_proof'],
            'agree_terms' => ['required', 'accepted'],
            'data_consent' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'phone.regex' => 'Enter a valid Philippine mobile number.',
            'agree_terms.accepted' => 'You must accept the booking terms.',
            'data_consent.accepted' => 'You must consent to the processing of booking information.',
            'payment_proof.required_without' => 'A payment proof is required.',
            'payment_proof_temp.required_without' => 'A payment proof is required.',
        ];
    }
}
