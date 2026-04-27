<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverProfileRequest extends FormRequest
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
            'license_no' => [
                'required',
                'string',
                'min:5',
                'max:50',
            ],

            'license_expiry' => [
                'required',
                'date',
                'after:today',
            ],

            'status' => [
                'required',
                'in:online,offline,idle,suspended',
            ],

            'document' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'license_no.required'      => 'License number is required.',
            'license_expiry.after'     => 'License expiry must be a future date.',
            'status.in'                => 'Invalid driver status.',
            'document.mimes'           => 'Document must be an image or PDF.',
            'document.max'             => 'Document size must not exceed 5MB.',
        ];
    }
}
