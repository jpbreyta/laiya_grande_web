<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class ContactMessageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'contact_subject_id' => ['nullable', 'integer', 'exists:contact_subjects,id', 'required_without:subject'],
            'subject' => ['nullable', 'string', 'max:255', 'required_without:contact_subject_id'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ];
    }
}
