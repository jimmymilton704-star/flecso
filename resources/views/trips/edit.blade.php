@extends('layouts.app')

@section('title', 'Edit Trip')
@section('body-class', 'page-dashboard')

@section('content')
    <style>
        .upload-box {
            display: block;
            border: 2px dashed #d1d5db;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fafafa;
            margin-bottom: 10px;
        }

        .upload-box:hover {
            border-color: #4f46e5;
            background: #f5f7ff;
        }

        .upload-content strong {
            display: block;
            font-size: 14px;
            color: #111827;
        }

        .upload-content span {
            font-size: 12px;
            color: #6b7280;
        }
    </style>

    <section class="page">
        <div class="page-head">
            <div>
                <div class="breadcrumb">Operations <span>/ Trips / Edit</span></div>
                <h1>Edit Trip</h1>
                <div class="page-head__sub">Update trip route, vehicle, and shipment details</div>
            </div>
        </div>

        <form action="{{ route('trips.update', $trip->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card">

                {{-- 1. BASIC TRIP INFO --}}
                <div class="card__head">
                    <h3>1. Basic Trip Information</h3>
                </div>

                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Trip ID*</label>
                            <input class="input" type="text" name="trip_id"
                                   value="{{ old('trip_id', $trip->trip_id) }}" required>
                        </div>

                        <div class="field">
                            <label>Trip Type*</label>
                            <input class="input" type="text" name="trip_type"
                                   value="{{ old('trip_type', $trip->trip_type) }}" required>
                        </div>

                        <div class="field">
                            <label>Status*</label>
                            <select name="trip_status" required>
                                <option value="pending" {{ $trip->trip_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_transit" {{ $trip->trip_status == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                <option value="completed" {{ $trip->trip_status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $trip->trip_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Schedule Date & Time</label>
                            <input class="input" type="datetime-local" name="schedule_datetime"
                                   value="{{ old('schedule_datetime', $trip->schedule_datetime) }}">
                        </div>

                        <div class="field">
                            <label>Payment Amount</label>
                            <input class="input" type="number" step="0.01" name="payment_amount"
                                   value="{{ old('payment_amount', $trip->payment_amount) }}">
                        </div>

                    </div>
                </div>

                {{-- 2. ROUTE + MAP --}}
                <div class="card__head">
                    <h3>2. Route Information (Map)</h3>
                </div>

                <div class="card__body">

                    <div class="form-grid">

                        <div class="field full">
                            <label>Pickup Location*</label>
                            <input class="input" id="pickup_input" type="text"
                                   name="pickup_location"
                                   value="{{ old('pickup_location', $trip->pickup_location) }}" required>
                        </div>

                        <div class="field full">
                            <label>Delivery Location*</label>
                            <input class="input" id="delivery_input" type="text"
                                   name="delivery_location"
                                   value="{{ old('delivery_location', $trip->delivery_location) }}" required>
                        </div>

                        <div class="field">
                            <label>Pickup Latitude</label>
                            <input class="input" id="pickup_lat" type="text"
                                   name="pickup_lat"
                                   value="{{ old('pickup_lat', $trip->pickup_lat) }}" readonly>
                        </div>

                        <div class="field">
                            <label>Pickup Longitude</label>
                            <input class="input" id="pickup_lng" type="text"
                                   name="pickup_lng"
                                   value="{{ old('pickup_lng', $trip->pickup_lng) }}" readonly>
                        </div>

                        <div class="field">
                            <label>Delivery Latitude</label>
                            <input class="input" id="delivery_lat" type="text"
                                   name="delivery_lat"
                                   value="{{ old('delivery_lat', $trip->delivery_lat) }}" readonly>
                        </div>

                        <div class="field">
                            <label>Delivery Longitude</label>
                            <input class="input" id="delivery_lng" type="text"
                                   name="delivery_lng"
                                   value="{{ old('delivery_lng', $trip->delivery_lng) }}" readonly>
                        </div>

                    </div>

                    <div id="map" style="height: 400px; border-radius: 12px; margin-top: 15px;"></div>

                </div>

                {{-- 3. ASSIGNMENTS --}}
                <div class="card__head">
                    <h3>3. Assignments</h3>
                </div>

                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Driver</label>
                            <select class="input" name="driver_id">
                                <option value="">Select Driver</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}"
                                        {{ $trip->driver_id == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Truck</label>
                            <select class="input" name="truck_id">
                                <option value="">Select Truck</option>
                                @foreach($trucks as $truck)
                                    <option value="{{ $truck->id }}"
                                        {{ $trip->truck_id == $truck->id ? 'selected' : '' }}>
                                        {{ $truck->truck_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Container</label>
                            <select class="input" name="container_id">
                                <option value="">Select Container</option>
                                @foreach($containers as $container)
                                    <option value="{{ $container->id }}"
                                        {{ $trip->container_id == $container->id ? 'selected' : '' }}>
                                        {{ $container->container_license_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="card__body">
                    <div style="display:flex; gap:10px;">
                        <a href="{{ route('trips.index') }}" class="btn btn--ghost">Cancel</a>
                        <button type="submit" class="btn btn--primary">Update Trip</button>
                    </div>
                </div>

            </div>
        </form>
    </section>

    {{-- GOOGLE MAPS --}}
      <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQqP59sFi_cXyk8Afq_AY4Dkg4DCf-xj0&libraries=places"></script>
    <script>

    <script>
        let map, pickupMarker, deliveryMarker, pickupAutocomplete, deliveryAutocomplete;

        function initMap() {

            const pickupLat = parseFloat("{{ $trip->pickup_lat ?? 24.8607 }}");
            const pickupLng = parseFloat("{{ $trip->pickup_lng ?? 67.0011 }}");

            const deliveryLat = parseFloat("{{ $trip->delivery_lat ?? 24.8607 }}");
            const deliveryLng = parseFloat("{{ $trip->delivery_lng ?? 67.0011 }}");

            const center = { lat: pickupLat, lng: pickupLng };

            map = new google.maps.Map(document.getElementById("map"), {
                center: center,
                zoom: 10,
            });

            pickupMarker = new google.maps.Marker({
                position: { lat: pickupLat, lng: pickupLng },
                map,
                draggable: true,
                label: "P"
            });

            deliveryMarker = new google.maps.Marker({
                position: { lat: deliveryLat, lng: deliveryLng },
                map,
                draggable: true,
                label: "D"
            });

            pickupAutocomplete = new google.maps.places.Autocomplete(document.getElementById("pickup_input"));
            deliveryAutocomplete = new google.maps.places.Autocomplete(document.getElementById("delivery_input"));

            pickupAutocomplete.addListener("place_changed", function () {
                const place = pickupAutocomplete.getPlace();
                if (!place.geometry) return;

                const loc = place.geometry.location;

                pickupMarker.setPosition(loc);
                map.setCenter(loc);

                document.getElementById("pickup_lat").value = loc.lat();
                document.getElementById("pickup_lng").value = loc.lng();
            });

            deliveryAutocomplete.addListener("place_changed", function () {
                const place = deliveryAutocomplete.getPlace();
                if (!place.geometry) return;

                const loc = place.geometry.location;

                deliveryMarker.setPosition(loc);
                map.setCenter(loc);

                document.getElementById("delivery_lat").value = loc.lat();
                document.getElementById("delivery_lng").value = loc.lng();
            });

            pickupMarker.addListener("dragend", function () {
                const pos = pickupMarker.getPosition();
                document.getElementById("pickup_lat").value = pos.lat();
                document.getElementById("pickup_lng").value = pos.lng();
            });

            deliveryMarker.addListener("dragend", function () {
                const pos = deliveryMarker.getPosition();
                document.getElementById("delivery_lat").value = pos.lat();
                document.getElementById("delivery_lng").value = pos.lng();
            });
        }

        window.onload = initMap;
    </script>
@endsection