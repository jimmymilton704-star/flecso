<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTruckRequest extends FormRequest
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
        $truckId = $this->route('id');

        return [
            'truck_code' => [
                'required',
                'string',
                Rule::unique('trucks', 'truck_code')->ignore($truckId),
            ],
            'number_plate' => [
                'required',
                'string',
                Rule::unique('trucks', 'number_plate')->ignore($truckId),
            ],
            'load_capacity'  => 'required|numeric|min:0',
            'type'           => ['required', Rule::in(['flatbed','box_truck','container','tanker'])],
            'status'         => ['required', Rule::in(['available','assigned','under_maintenance'])],
            'start_latitude' => 'nullable|numeric',
            'start_longitude'=> 'nullable|numeric',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'truck_code.unique'   => 'Truck code already exists.',
            'number_plate.unique' => 'Number plate already exists.',
        ];
    }
}
