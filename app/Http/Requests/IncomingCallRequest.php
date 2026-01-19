<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IncomingCallRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'caller_id' => ['required', 'string', 'max:11'],
            'call_type' => ['nullable', 'string', 'in:incoming,outgoing'],
            'timestamp' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'caller_id.required' => 'شماره تماس الزامی است.',
            'caller_id.max' => 'شماره تماس نباید بیش از 11 کاراکتر باشد.',
            'call_type.in' => 'نوع تماس باید ورودی یا خروجی باشد.',
            'timestamp.date' => 'زمان باید یک تاریخ معتبر باشد.',
        ];
    }
}
