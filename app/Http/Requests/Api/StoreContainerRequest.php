<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContainerRequest extends FormRequest
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
        $containerId = $this->route('id');

        return [
            'container_code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('containers', 'container_code')->ignore($containerId),
            ],
            'weight_capacity' => 'required|numeric|min:0',
            'type'             => 'required|string|in:20ft,40ft,refrigerated',
            'status'           => 'required|string|in:available,assigned,under_maintenance',
            'start_point'      => 'required|string|max:255',
            'end_point'        => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'container_code.required' => 'Container code is required.',
            'container_code.unique'   => 'Container code already exists.',
            'weight_capacity.numeric' => 'Weight capacity must be a number.',
            'type.in'                 => 'Invalid container type.',
            'status.in'               => 'Invalid container status.',
        ];
    }
}
