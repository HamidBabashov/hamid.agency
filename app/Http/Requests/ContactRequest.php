<?php

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^[0-9 +()\-]{7,30}$/'],
            'service_type' => ['nullable', Rule::in([...Service::KEYS, 'other'])],
            'message' => ['required', 'string', 'min:10', 'max:3000'],
        ];
    }

    public function attributes(): array
    {
        return __('site.attributes');
    }
}
