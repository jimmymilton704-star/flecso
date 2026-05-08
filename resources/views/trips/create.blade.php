@extends('layouts.app')

@section('title', 'Add Trip')
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
                <div class="breadcrumb">Operations <span>/ Trips / Add</span></div>
                <h1>Add New Trip</h1>
                <div class="page-head__sub">Create a new trip with route, vehicle, and shipment details</div>
            </div>
        </div>

        <form action="{{ route('trips.store') }}" method="POST">
            @csrf

            <div class="card">

                {{-- 1. BASIC TRIP INFO --}}
                <div class="card__head">
                    <h3>1. Basic Trip Information</h3>
                </div>

                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Trip ID*</label>
                            <input class="input" type="text" name="trip_id" required value="{{ old('trip_id') }}">
                        </div>

                        <div class="field">
                            <label>Trip Type*</label>
                            <input class="input" type="text" name="trip_type" required value="{{ old('trip_type') }}"
                                placeholder="e.g. Import / Export">
                        </div>

                        <div class="field">
                            <label>Status*</label>
                            <select name="trip_status" required>
                                <option value="pending">Pending</option>
                                <option value="in_transit">In Transit</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Schedule Date & Time</label>
                            <input class="input" type="datetime-local" name="schedule_datetime"
                                value="{{ old('schedule_datetime') }}">
                        </div>

                        <div class="field">
                            <label>Payment Amount</label>
                            <input class="input" type="number" step="0.01" name="payment_amount"
                                value="{{ old('payment_amount') }}">
                        </div>

                    </div>
                </div>

                {{-- 2. ROUTE INFORMATION --}}
                <div class="card__head">
                    <h3>2. Route Information (Map)</h3>
                </div>

                <div class="card__body">

                    <div class="form-grid">

                        <div class="field full">
                            <label>Pickup Location*</label>
                            <input class="input" id="pickup_input" type="text" name="pickup_location" required>
                        </div>

                        <div class="field full">
                            <label>Delivery Location*</label>
                            <input class="input" id="delivery_input" type="text" name="delivery_location" required>
                        </div>

                        <div class="field">
                            <label>Pickup Latitude</label>
                            <input class="input" id="pickup_lat" type="text" name="pickup_lat" readonly>
                        </div>

                        <div class="field">
                            <label>Pickup Longitude</label>
                            <input class="input" id="pickup_lng" type="text" name="pickup_lng" readonly>
                        </div>

                        <div class="field">
                            <label>Delivery Latitude</label>
                            <input class="input" id="delivery_lat" type="text" name="delivery_lat" readonly>
                        </div>

                        <div class="field">
                            <label>Delivery Longitude</label>
                            <input class="input" id="delivery_lng" type="text" name="delivery_lng" readonly>
                        </div>

                        <div class="field">
                            <label>Distance (KM)</label>
                            <input class="input" id="distance_km" type="text" name="distance_km" readonly>
                        </div>

                        <div class="field">
                            <label>ETA (Minutes)</label>
                            <input class="input" id="eta_mins" type="text" name="eta_mins" readonly>
                        </div>

                    </div>

                    {{-- MAP --}}
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
                                    <option value="{{ $driver->id }}">
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
                                    <option value="{{ $truck->id }}">
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
                                    <option value="{{ $container->id }}">
                                        {{ $container->container_license_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                {{-- 4. DELIVERY DETAILS --}}
                <div class="card__head">
                    <h3>4. Delivery Details</h3>
                </div>

                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Receiver Name</label>
                            <input class="input" type="text" name="delivery_name">
                        </div>

                        <div class="field">
                            <label>Receiver Phone</label>
                            <input class="input" type="text" name="delivery_phone">
                        </div>

                        <div class="field">
                            <label>Receiver Email</label>
                            <input class="input" type="email" name="delivery_email">
                        </div>

                    </div>
                </div>

                {{-- 5. PACKAGE INFO --}}
                <div class="card__head">
                    <h3>5. Package Information</h3>
                </div>

                <div class="card__body">
                    <div class="form-grid">

                        <div class="field full">
                            <label>Description</label>
                            <input class="input" type="text" name="package_description">
                        </div>

                        <div class="field">
                            <label>Weight (KG)</label>
                            <input class="input" type="number" step="any" name="package_weight">
                        </div>

                        <div class="field">
                            <label>Height</label>
                            <input class="input" type="number" step="any" name="package_height">
                        </div>

                        <div class="field">
                            <label>Length</label>
                            <input class="input" type="number" step="any" name="package_length">
                        </div>

                        <div class="field">
                            <label>Width</label>
                            <input class="input" type="number" step="any" name="package_width">
                        </div>

                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="card__body">
                    <div style="display:flex; gap:10px;">
                        <a href="{{ route('trips.index') }}" class="btn btn--ghost">Cancel</a>
                        <button type="submit" class="btn btn--primary">Save Trip</button>
                    </div>
                </div>

            </div>
        </form>
    </section>

    <script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQqP59sFi_cXyk8Afq_AY4Dkg4DCf-xj0&libraries=places"></script>

<script>
    let map;
    let pickupMarker;
    let deliveryMarker;
    let pickupAutocomplete;
    let deliveryAutocomplete;
    let directionsService;
    let directionsRenderer;

    function initMap() {

        const defaultCenter = {
            lat: 24.8607,
            lng: 67.0011
        };

        // SERVICES
        directionsService = new google.maps.DirectionsService();

        directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: "#FF6B1A",
                strokeWeight: 6
            }
        });

        // MAP
        map = new google.maps.Map(document.getElementById("map"), {
            center: defaultCenter,
            zoom: 6,
        });

        directionsRenderer.setMap(map);

        // MARKERS
        pickupMarker = new google.maps.Marker({
            map: map,
            draggable: true,
            label: "P"
        });

        deliveryMarker = new google.maps.Marker({
            map: map,
            draggable: true,
            label: "D"
        });

        // AUTOCOMPLETE
        pickupAutocomplete = new google.maps.places.Autocomplete(
            document.getElementById("pickup_input")
        );

        deliveryAutocomplete = new google.maps.places.Autocomplete(
            document.getElementById("delivery_input")
        );

        // ------------------------
        // PICKUP SELECT
        // ------------------------
        pickupAutocomplete.addListener("place_changed", function () {

            const place = pickupAutocomplete.getPlace();

            if (!place.geometry) return;

            const location = place.geometry.location;

            pickupMarker.setPosition(location);

            document.getElementById("pickup_lat").value = location.lat();
            document.getElementById("pickup_lng").value = location.lng();

            calculateRoute();
        });

        // ------------------------
        // DELIVERY SELECT
        // ------------------------
        deliveryAutocomplete.addListener("place_changed", function () {

            const place = deliveryAutocomplete.getPlace();

            if (!place.geometry) return;

            const location = place.geometry.location;

            deliveryMarker.setPosition(location);

            document.getElementById("delivery_lat").value = location.lat();
            document.getElementById("delivery_lng").value = location.lng();

            calculateRoute();
        });

        // ------------------------
        // DRAG PICKUP
        // ------------------------
        pickupMarker.addListener("dragend", function () {

            const pos = pickupMarker.getPosition();

            document.getElementById("pickup_lat").value = pos.lat();
            document.getElementById("pickup_lng").value = pos.lng();

            calculateRoute();
        });

        // ------------------------
        // DRAG DELIVERY
        // ------------------------
        deliveryMarker.addListener("dragend", function () {

            const pos = deliveryMarker.getPosition();

            document.getElementById("delivery_lat").value = pos.lat();
            document.getElementById("delivery_lng").value = pos.lng();

            calculateRoute();
        });
    }

    // =========================================
    // CALCULATE ROUTE
    // =========================================
    function calculateRoute() {

        const pickup = pickupMarker.getPosition();
        const delivery = deliveryMarker.getPosition();

        if (!pickup || !delivery) return;

        directionsService.route({
                origin: pickup,
                destination: delivery,
                travelMode: google.maps.TravelMode.DRIVING
            },
            function(response, status) {

                if (status === "OK") {

                    // DRAW ROUTE
                    directionsRenderer.setDirections(response);

                    // ROUTE DATA
                    const route = response.routes[0].legs[0];

                    // DISTANCE KM
                    const distanceKm = (
                        route.distance.value / 1000
                    ).toFixed(2);

                    // ETA MINUTES
                    const durationMin = Math.round(
                        route.duration.value / 60
                    );

                    document.getElementById("distance_km").value = distanceKm;

                    document.getElementById("eta_mins").value = durationMin;

                    // FIT MAP TO ROUTE
                    const bounds = new google.maps.LatLngBounds();

                    bounds.extend(pickup);
                    bounds.extend(delivery);

                    map.fitBounds(bounds);

                } else {

                    console.log("Directions request failed due to " + status);

                }
            });
    }

    // INIT
    window.onload = initMap;
</script>
@endsection