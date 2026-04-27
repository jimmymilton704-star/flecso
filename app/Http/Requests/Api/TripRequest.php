<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class TripRequest extends FormRequest
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
            'trip_code' => 'required|string|max:50',
            'pickup_delivery' => 'required|string|in:standard,express',

            'driver_id' => 'required|integer|exists:users,id',
            'truck_id' => 'required|integer|exists:trucks,id',
            'container_id' => 'required|integer|exists:containers,id',

            'pickup_location' => 'required|string|max:255',
            'destination_location' => 'required|string|max:255',

            'distance' => 'required|numeric',
            'estimated_time' => 'required|numeric',

            'date_time' => 'required|date_format:Y-m-d H:i:s',
            'payment_amount' => 'required|numeric',

            'status' => 'required|string|in:pending,active,completed,cancelled',

            'delivery_person_name' => 'required|string|max:100',
            'delivery_person_contact' => 'required|string|max:20',
            'delivery_person_email' => 'nullable|email|max:100',

            'package_description' => 'nullable|string',
            'package_weight' => 'nullable|numeric',
            'package_height' => 'nullable|numeric',
            'package_length' => 'nullable|numeric',
            'package_width' => 'nullable|numeric',

            'start_location' => 'nullable|string|max:255',
            'item_location' => 'nullable|string|max:255',
            'second_item_location' => 'nullable|string|max:255',
            'third_item_location' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'driver_id.exists' => 'The selected driver does not exist.',
            'truck_id.exists' => 'The selected truck does not exist.',
            'container_id.exists' => 'The selected container does not exist.',
        ];
    }
}
