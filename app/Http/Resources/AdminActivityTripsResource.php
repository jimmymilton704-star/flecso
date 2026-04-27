<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminActivityTripsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'trip_code'            => $this->trip_code,
            'pickup_delivery'      => $this->pickup_delivery,

            'driver' => $this->driver ? [
                'id'   => $this->driver->id,
                'name' => $this->driver->name,
            ] : null,

            'truck'             => $this->truck ? [
                                            'id'                => $this->truck->id,
                                            'truck_code'        => $this->truck->truck_code,
                                            'number_plate'      => $this->truck->number_plate,
                                            'type'              => $this->truck->type
                                        ] : null,
            'container'         => $this->container ? [
                                            'id'                => $this->container->id,
                                            'container_code'        => $this->container->container_code,
                                            'weight_capacity'      => $this->container->weight_capacity,
                                            'type'              => $this->container->type
                                        ] : null,
            'pickup_location'      => $this->pickup_location,
            'destination_location' => $this->destination_location,
            'distance'             => $this->distance,
            'estimated_time'       => $this->estimated_time,
            'date_time'            => $this->date_time,
            'payment_amount'       => $this->payment_amount,
            'status'               => $this->status,

            'admin' => $this->admin ? [
                'id'   => $this->admin->id,
                'name' => $this->admin->name,
            ] : null,

            'delivery_person_name'    => $this->delivery_person_name,
            'delivery_person_contact' => $this->delivery_person_contact,
            'delivery_person_email'   => $this->delivery_person_email,
            'package_description'     => $this->package_description,
            'package_weight'          => $this->package_weight,
            'package_length'          => $this->package_length,
            'package_width'           => $this->package_width,
            'start_location'          => $this->start_location,
            'item_location'           => $this->item_location,
            'second_item_location'    => $this->second_item_location,
            'third_item_location'     => $this->third_item_location,
        ];
    }
}
