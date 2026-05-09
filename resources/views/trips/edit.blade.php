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
                <div class="page-head__sub">Update trip details, route and assignments</div>
            </div>
        </div>

        <form action="{{ route('trips.update', $trip->id) }}" method="POST">
            @csrf

            <div class="card">

                {{-- 1. BASIC INFO --}}
                <div class="card__head">
                    <h3>1. Basic Trip Information</h3>
                </div>

                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Trip ID</label>
                            <input class="input" type="text" name="trip_id" value="{{ old('trip_id', $trip->trip_id) }}">
                        </div>

                        <div class="field">
                            <label>Trip Type</label>
                            <input class="input" type="text" name="trip_type"
                                value="{{ old('trip_type', $trip->trip_type) }}">
                        </div>

                        <div class="field">
                            <label>Status</label>
                            <select name="trip_status">
                                <option value="pending" {{ $trip->trip_status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="in_transit" {{ $trip->trip_status == 'in_transit' ? 'selected' : '' }}>In
                                    Transit</option>
                                <option value="completed" {{ $trip->trip_status == 'completed' ? 'selected' : '' }}>Completed
                                </option>
                                <option value="cancelled" {{ $trip->trip_status == 'cancelled' ? 'selected' : '' }}>Cancelled
                                </option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Schedule</label>
                            <input class="input" type="datetime-local" name="schedule_datetime"
                                value="{{ old('schedule_datetime', $trip->schedule_datetime) }}">
                        </div>

                        <div class="field">
                            <label>Payment</label>
                            <input class="input" type="number" name="payment_amount"
                                value="{{ old('payment_amount', $trip->payment_amount) }}">
                        </div>

                    </div>
                </div>

                {{-- 2. ROUTE --}}
                <div class="card__head">
                    <h3>2. Route (Map)</h3>
                </div>

                <div class="card__body">

                    <div class="form-grid">

                        <div class="field full">
                            <label>Pickup</label>
                            <input id="pickup_input" class="input" name="pickup_location"
                                value="{{ old('pickup_location', $trip->pickup_location) }}">
                        </div>

                        <div class="field full">
                            <label>Delivery</label>
                            <input id="delivery_input" class="input" name="delivery_location"
                                value="{{ old('delivery_location', $trip->delivery_location) }}">
                        </div>

                        <div class="field">
                            <label>Pickup Lat</label>
                            <input id="pickup_lat" class="input" name="pickup_lat" value="{{ $trip->pickup_lat }}" readonly>
                        </div>

                        <div class="field">
                            <label>Pickup Lng</label>
                            <input id="pickup_lng" class="input" name="pickup_lng" value="{{ $trip->pickup_lng }}" readonly>
                        </div>

                        <div class="field">
                            <label>Delivery Lat</label>
                            <input id="delivery_lat" class="input" name="delivery_lat" value="{{ $trip->delivery_lat }}"
                                readonly>
                        </div>

                        <div class="field">
                            <label>Delivery Lng</label>
                            <input id="delivery_lng" class="input" name="delivery_lng" value="{{ $trip->delivery_lng }}"
                                readonly>
                        </div>

                        <div class="field">
                            <label>Distance (KM)</label>
                            <input id="distance_km" class="input" name="distance_km" value="{{ $trip->distance_km }}"
                                readonly>
                        </div>

                        <div class="field">
                            <label>ETA (Minutes)</label>
                            <input id="eta_mins" class="input" name="eta_mins" value="{{ $trip->eta_mins }}" readonly>
                        </div>

                    </div>

                    <div id="map" style="height:400px;margin-top:15px;border-radius:10px;"></div>

                </div>

                {{-- 3. ASSIGNMENTS --}}
                <div class="card__head">
                    <h3>3. Assignments</h3>
                </div>

                <div class="card__body">
                    <div class="form-grid">

                        <div class="field">
                            <label>Driver</label>
                            <select name="driver_id">
                                <option value="">Select</option>
                                @foreach($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ $trip->driver_id == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Truck</label>
                            <select name="truck_id">
                                <option value="">Select</option>
                                @foreach($trucks as $truck)
                                    <option value="{{ $truck->id }}" {{ $trip->truck_id == $truck->id ? 'selected' : '' }}>
                                        {{ $truck->truck_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Container</label>
                            <select name="container_id">
                                <option value="">Select</option>
                                @foreach($containers as $container)
                                    <option value="{{ $container->id }}" {{ $trip->container_id == $container->id ? 'selected' : '' }}>
                                        {{ $container->container_license_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>

                {{-- SUBMIT --}}
                <div class="card__body">
                    <button class="btn btn--primary">Update Trip</button>
                </div>

            </div>
        </form>
    </section>

    {{-- GOOGLE MAP --}}
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQqP59sFi_cXyk8Afq_AY4Dkg4DCf-xj0&libraries=places"></script>

    <script>
let map, pickupMarker, deliveryMarker;
let directionsService, directionsRenderer;

function initMap() {

    const pickup = {
        lat: parseFloat("{{ $trip->pickup_lat ?? 24.8607 }}"),
        lng: parseFloat("{{ $trip->pickup_lng ?? 67.0011 }}")
    };

    const delivery = {
        lat: parseFloat("{{ $trip->delivery_lat ?? 24.8607 }}"),
        lng: parseFloat("{{ $trip->delivery_lng ?? 67.0011 }}")
    };

    directionsService = new google.maps.DirectionsService();

    directionsRenderer = new google.maps.DirectionsRenderer({
        suppressMarkers: true, // ❗ important (we use custom markers)
        polylineOptions: {
            strokeColor: "#FF6B1A",
            strokeWeight: 5
        }
    });

    map = new google.maps.Map(document.getElementById("map"), {
        center: pickup,
        zoom: 7,
    });

    directionsRenderer.setMap(map);

    // ------------------------
    // CUSTOM MARKERS
    // ------------------------
    pickupMarker = new google.maps.Marker({
        position: pickup,
        map: map,
        draggable: true,
        label: "P"
    });

    deliveryMarker = new google.maps.Marker({
        position: delivery,
        map: map,
        draggable: true,
        label: "D"
    });

    // Initial route draw
    calculateRoute();

    // Drag events
    pickupMarker.addListener("dragend", updatePickup);
    deliveryMarker.addListener("dragend", updateDelivery);
}

// ------------------------
// UPDATE PICKUP
// ------------------------
function updatePickup() {
    const p = pickupMarker.getPosition();

    document.getElementById("pickup_lat").value = p.lat();
    document.getElementById("pickup_lng").value = p.lng();

    calculateRoute();
}

// ------------------------
// UPDATE DELIVERY
// ------------------------
function updateDelivery() {
    const d = deliveryMarker.getPosition();

    document.getElementById("delivery_lat").value = d.lat();
    document.getElementById("delivery_lng").value = d.lng();

    calculateRoute();
}

// ------------------------
// CALCULATE + DRAW ROUTE
// ------------------------
function calculateRoute() {

    directionsService.route({
        origin: pickupMarker.getPosition(),
        destination: deliveryMarker.getPosition(),
        travelMode: "DRIVING"
    }, function (res, status) {

        if (status === "OK") {

            // 🔥 DRAW LINE
            directionsRenderer.setDirections(res);

            const leg = res.routes[0].legs[0];

            // Distance KM
            document.getElementById("distance_km").value =
                (leg.distance.value / 1000).toFixed(2);

            // ETA Minutes
            document.getElementById("eta_mins").value =
                Math.round(leg.duration.value / 60);
        }
    });
}

window.onload = initMap;
</script>

@endsection