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

        .payment-suggestion {
            margin-top: 12px;
            padding: 14px;
            border-radius: 14px;
            background: #fff7ed;
            border: 1px solid #fed7aa;
        }

        .payment-suggestion__header {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 10px;
        }

        .payment-suggestion__header strong {
            display: block;
            color: #0f172a;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .payment-suggestion__header span {
            display: block;
            color: #9a3412;
            font-size: 12px;
            line-height: 1.4;
        }

        .previous-trip-card {
            margin-top: 10px;
            padding: 13px;
            border-radius: 13px;
            border: 1px solid #fed7aa;
            box-shadow: 0 8px 20px rgba(245, 158, 11, .08);
        }

        .previous-trip-card__head {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 8px;
        }

        .previous-trip-card__title {
            font-size: 13px;
            font-weight: 900;
            color: #0f172a;
        }

        .previous-trip-card__amount {
            font-size: 13px;
            font-weight: 900;
            color: #c2410c;
            white-space: nowrap;
        }

        .previous-trip-card__meta {
            font-size: 12px;
            line-height: 1.7;
        }

        .previous-trip-card__meta strong {
            color: #0f172a;
            font-weight: 800;
        }

        .previous-trip-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
            flex-wrap: wrap;
        }

        .previous-trip-actions button {
            border: none;
            padding: 8px 11px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 800;
            cursor: pointer;
        }

        .use-payment-btn {
            background: linear-gradient(135deg, #f59e0b, #fb923c);
            color: #ffffff;
        }

        .use-all-btn {
            background: #111827;
            color: #ffffff;
        }

        .account-preview-btn {
            background: #0f172a;
            color: #f59e0b;
            border: 1px solid #1f2937 !important;
        }

        .use-payment-btn:hover,
        .use-all-btn:hover,
        .account-preview-btn:hover {
            opacity: .92;
        }

        .previous-account-modal {
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, .65);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 24px;
            z-index: 99999;
        }

        .previous-account-modal.active {
            display: flex;
        }

        .previous-account-modal__box {
            width: 100%;
            max-width: 980px;
            max-height: 88vh;
            background: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(15, 23, 42, .35);
        }

        .previous-account-modal__head {
            padding: 18px 22px;
            background: linear-gradient(180deg, #15151b, #0b0b0f);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .previous-account-modal__head h3 {
            margin: 0;
            color: #ffffff;
            font-size: 19px;
            font-weight: 900;
        }

        .previous-account-modal__head span {
            display: block;
            color: rgba(255, 255, 255, .65);
            font-size: 13px;
            margin-top: 4px;
        }

        .previous-account-modal__close {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: 1px solid rgba(255,255,255,.14);
            background: rgba(255,255,255,.06);
            color: #fff;
            cursor: pointer;
            font-size: 22px;
        }

        .previous-account-modal__body {
            padding: 22px;
            max-height: calc(88vh - 78px);
            overflow-y: auto;
        }

        .previous-account-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 18px;
        }

        .previous-account-stat {
            padding: 14px;
            border-radius: 14px;
            border: 1px solid #e5e7eb;
            background: #f8fafc;
        }

        .previous-account-stat.opening {
            background: #fff7ed;
            border-color: #fed7aa;
        }

        .previous-account-stat.expense {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .previous-account-stat.remaining {
            background: #ecfdf5;
            border-color: #bbf7d0;
        }

        .previous-account-stat span {
            display: block;
            font-size: 12px;
            color: #64748b;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .previous-account-stat strong {
            display: block;
            font-size: 21px;
            color: #0f172a;
            font-weight: 900;
        }

        .previous-account-info {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 18px;
        }

        .previous-account-info div {
            padding: 11px 12px;
            border-radius: 12px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            font-size: 13px;
        }

        .previous-account-info span {
            display: block;
            color: #64748b;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .previous-account-info strong {
            color: #0f172a;
        }

        .previous-account-table-wrap {
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
        }

        .previous-account-table {
            width: 100%;
            min-width: 850px;
            border-collapse: collapse;
        }

        .previous-account-table th {
            background: #0f172a;
            color: rgba(255,255,255,.78);
            padding: 11px;
            text-align: left;
            font-size: 12px;
            white-space: nowrap;
        }

        .previous-account-table td {
            padding: 11px;
            border-bottom: 1px solid #eef2f7;
            font-size: 13px;
            color: #334155;
            vertical-align: top;
        }

        .previous-account-table tr:last-child td {
            border-bottom: 0;
        }

        .previous-account-empty {
            padding: 28px;
            text-align: center;
            border: 1px dashed #cbd5e1;
            border-radius: 14px;
            color: #64748b;
            background: #f8fafc;
        }

        @media (max-width: 768px) {
            .previous-account-stats,
            .previous-account-info {
                grid-template-columns: 1fr;
            }
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
                                <option value="pending" {{ old('trip_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="in_transit" {{ old('trip_status') == 'in_transit' ? 'selected' : '' }}>In Transit</option>
                                <option value="completed" {{ old('trip_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('trip_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Schedule Date & Time</label>
                            <input class="input" type="datetime-local" name="schedule_datetime"
                                value="{{ old('schedule_datetime') }}">
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
                            <input class="input" id="pickup_input" type="text" name="pickup_location"
                                value="{{ old('pickup_location') }}" required>
                        </div>

                        <div class="field full">
                            <label>Delivery Location*</label>
                            <input class="input" id="delivery_input" type="text" name="delivery_location"
                                value="{{ old('delivery_location') }}" required>
                        </div>

                        <div class="field">
                            <label>Pickup Latitude</label>
                            <input class="input" id="pickup_lat" type="text" name="pickup_lat"
                                value="{{ old('pickup_lat') }}" readonly>
                        </div>

                        <div class="field">
                            <label>Pickup Longitude</label>
                            <input class="input" id="pickup_lng" type="text" name="pickup_lng"
                                value="{{ old('pickup_lng') }}" readonly>
                        </div>

                        <div class="field">
                            <label>Delivery Latitude</label>
                            <input class="input" id="delivery_lat" type="text" name="delivery_lat"
                                value="{{ old('delivery_lat') }}" readonly>
                        </div>

                        <div class="field">
                            <label>Delivery Longitude</label>
                            <input class="input" id="delivery_lng" type="text" name="delivery_lng"
                                value="{{ old('delivery_lng') }}" readonly>
                        </div>

                        <div class="field">
                            <label>Distance (KM)</label>
                            <input class="input" id="distance_km" type="text" name="distance_km"
                                value="{{ old('distance_km') }}" readonly>
                        </div>

                        <div class="field">
                            <label>ETA (Minutes)</label>
                            <input class="input" id="eta_mins" type="text" name="eta_mins"
                                value="{{ old('eta_mins') }}" readonly>
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
                            <select class="input" name="driver_id" id="driver_id">
                                <option value="">Select Driver</option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->id }}" {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->full_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Truck</label>
                            <select class="input" name="truck_id" id="truck_id">
                                <option value="">Select Truck</option>
                                @foreach ($trucks as $truck)
                                    <option value="{{ $truck->id }}" {{ old('truck_id') == $truck->id ? 'selected' : '' }}>
                                        {{ $truck->truck_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label>Container</label>
                            <select class="input" name="container_id" id="container_id">
                                <option value="">Select Container</option>
                                @foreach ($containers as $container)
                                    <option value="{{ $container->id }}" {{ old('container_id') == $container->id ? 'selected' : '' }}>
                                        {{ $container->container_license_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field full">
                            <label>Payment Amount</label>
                            <input class="input" id="payment_amount" type="number" step="0.01" name="payment_amount"
                                value="{{ old('payment_amount') }}">

                            <div id="paymentSuggestionBox" class="payment-suggestion" style="display:none;">
                                <div class="payment-suggestion__header">
                                    <div>
                                        <strong id="suggestionTitle">Previous trip found</strong>
                                        <span id="suggestionText">You can reuse payment or previous trip details for this route.</span>
                                    </div>
                                </div>

                                <div id="previousTripsList"></div>
                            </div>
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
                            <input class="input" id="delivery_name" type="text" name="delivery_name"
                                value="{{ old('delivery_name') }}">
                        </div>

                        <div class="field">
                            <label>Receiver Phone</label>
                            <input class="input" id="delivery_phone" type="text" name="delivery_phone"
                                value="{{ old('delivery_phone') }}">
                        </div>

                        <div class="field">
                            <label>Receiver Email</label>
                            <input class="input" id="delivery_email" type="email" name="delivery_email"
                                value="{{ old('delivery_email') }}">
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
                            <input class="input" id="package_description" type="text" name="package_description"
                                value="{{ old('package_description') }}">
                        </div>

                        <div class="field">
                            <label>Weight (KG)</label>
                            <input class="input" id="package_weight" type="number" step="any" name="package_weight"
                                value="{{ old('package_weight') }}">
                        </div>

                        <div class="field">
                            <label>Height</label>
                            <input class="input" id="package_height" type="number" step="any" name="package_height"
                                value="{{ old('package_height') }}">
                        </div>

                        <div class="field">
                            <label>Length</label>
                            <input class="input" id="package_length" type="number" step="any" name="package_length"
                                value="{{ old('package_length') }}">
                        </div>

                        <div class="field">
                            <label>Width</label>
                            <input class="input" id="package_width" type="number" step="any" name="package_width"
                                value="{{ old('package_width') }}">
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

    {{-- PREVIOUS ACCOUNT DETAIL MODAL --}}
    <div class="previous-account-modal" id="previousAccountModal">
        <div class="previous-account-modal__box">

            <div class="previous-account-modal__head">
                <div>
                    <h3 id="previousAccountModalTitle">Previous Account Details</h3>
                    <span id="previousAccountModalSub">Trip account and transactions</span>
                </div>

                <button type="button" class="previous-account-modal__close" onclick="closePreviousAccountModal()">
                    &times;
                </button>
            </div>

            <div class="previous-account-modal__body" id="previousAccountModalBody">
                {{-- JS will render account details here --}}
            </div>

        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQqP59sFi_cXyk8Afq_AY4Dkg4DCf-xj0&libraries=places"></script>

    <script>
        let map;
        let pickupMarker;
        let deliveryMarker;
        let pickupAutocomplete;
        let deliveryAutocomplete;
        let directionsService;
        let directionsRenderer;
        let previousTrips = [];

        function initMap() {
            const defaultCenter = {
                lat: 24.8607,
                lng: 67.0011
            };

            directionsService = new google.maps.DirectionsService();

            directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true,
                polylineOptions: {
                    strokeColor: "#FF6B1A",
                    strokeWeight: 6
                }
            });

            map = new google.maps.Map(document.getElementById("map"), {
                center: defaultCenter,
                zoom: 6,
            });

            directionsRenderer.setMap(map);

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

            pickupAutocomplete = new google.maps.places.Autocomplete(
                document.getElementById("pickup_input")
            );

            deliveryAutocomplete = new google.maps.places.Autocomplete(
                document.getElementById("delivery_input")
            );

            pickupAutocomplete.addListener("place_changed", function () {
                const place = pickupAutocomplete.getPlace();

                if (!place.geometry) return;

                const location = place.geometry.location;

                pickupMarker.setPosition(location);

                document.getElementById("pickup_lat").value = location.lat();
                document.getElementById("pickup_lng").value = location.lng();

                calculateRoute();
            });

            deliveryAutocomplete.addListener("place_changed", function () {
                const place = deliveryAutocomplete.getPlace();

                if (!place.geometry) return;

                const location = place.geometry.location;

                deliveryMarker.setPosition(location);

                document.getElementById("delivery_lat").value = location.lat();
                document.getElementById("delivery_lng").value = location.lng();

                calculateRoute();
            });

            pickupMarker.addListener("dragend", function () {
                const pos = pickupMarker.getPosition();

                document.getElementById("pickup_lat").value = pos.lat();
                document.getElementById("pickup_lng").value = pos.lng();

                calculateRoute();
            });

            deliveryMarker.addListener("dragend", function () {
                const pos = deliveryMarker.getPosition();

                document.getElementById("delivery_lat").value = pos.lat();
                document.getElementById("delivery_lng").value = pos.lng();

                calculateRoute();
            });

            restoreOldLatLngMarkers();
        }

        function restoreOldLatLngMarkers() {
            const pickupLat = document.getElementById("pickup_lat").value;
            const pickupLng = document.getElementById("pickup_lng").value;
            const deliveryLat = document.getElementById("delivery_lat").value;
            const deliveryLng = document.getElementById("delivery_lng").value;

            if (pickupLat && pickupLng) {
                pickupMarker.setPosition({
                    lat: parseFloat(pickupLat),
                    lng: parseFloat(pickupLng)
                });
            }

            if (deliveryLat && deliveryLng) {
                deliveryMarker.setPosition({
                    lat: parseFloat(deliveryLat),
                    lng: parseFloat(deliveryLng)
                });
            }

            if (pickupLat && pickupLng && deliveryLat && deliveryLng) {
                calculateRoute();
            }
        }

        function calculateRoute() {
            const pickup = pickupMarker.getPosition();
            const delivery = deliveryMarker.getPosition();

            if (!pickup || !delivery) return;

            directionsService.route({
                    origin: pickup,
                    destination: delivery,
                    travelMode: google.maps.TravelMode.DRIVING
                },
                function (response, status) {
                    if (status === "OK") {
                        directionsRenderer.setDirections(response);

                        const route = response.routes[0].legs[0];

                        const distanceKm = (route.distance.value / 1000).toFixed(2);
                        const durationMin = Math.round(route.duration.value / 60);

                        document.getElementById("distance_km").value = distanceKm;
                        document.getElementById("eta_mins").value = durationMin;

                        checkPreviousPaymentSuggestion();

                        const bounds = new google.maps.LatLngBounds();

                        bounds.extend(pickup);
                        bounds.extend(delivery);

                        map.fitBounds(bounds);
                    } else {
                        console.log("Directions request failed due to " + status);
                    }
                });
        }

        function checkPreviousPaymentSuggestion() {
            clearTimeout(window.paymentSuggestionTimer);

            window.paymentSuggestionTimer = setTimeout(function () {
                const pickupLat = document.getElementById("pickup_lat").value;
                const pickupLng = document.getElementById("pickup_lng").value;
                const deliveryLat = document.getElementById("delivery_lat").value;
                const deliveryLng = document.getElementById("delivery_lng").value;

                const suggestionBox = document.getElementById("paymentSuggestionBox");
                const previousTripsList = document.getElementById("previousTripsList");
                const suggestionText = document.getElementById("suggestionText");

                if (!pickupLat || !pickupLng || !deliveryLat || !deliveryLng) {
                    suggestionBox.style.display = "none";
                    return;
                }

                const params = new URLSearchParams({
                    pickup_lat: pickupLat,
                    pickup_lng: pickupLng,
                    delivery_lat: deliveryLat,
                    delivery_lng: deliveryLng
                });

                fetch("{{ route('trips.payment-suggestion') }}?" + params.toString(), {
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest"
                    }
                })
                .then(async response => {
                    const text = await response.text();
                    console.log("Payment suggestion raw response:", text);

                    try {
                        return JSON.parse(text);
                    } catch (error) {
                        console.log("Payment suggestion raw response:", text);
                        throw new Error("Payment suggestion response is not JSON.");
                    }
                })
                .then(result => {
                    previousTripsList.innerHTML = "";
                    previousTrips = [];

                    if (result.status === true && result.data && result.data.length > 0) {
                        previousTrips = result.data;

                        suggestionText.innerHTML = result.data.length + " previous trip(s) found for this route. You can reuse payment, details, or view account expenses.";

                        result.data.forEach((trip, index) => {
                            const card = document.createElement("div");
                            card.className = "previous-trip-card";

                            const accountSummary = trip.account
                                ? `<div><strong>Account:</strong> Opening Rs ${Number(trip.account.opening_amount ?? 0).toLocaleString()} | Expense Rs ${Number(trip.account.total_expense ?? 0).toLocaleString()} | Remaining Rs ${Number(trip.account.remaining_amount ?? 0).toLocaleString()}</div>`
                                : `<div><strong>Account:</strong> No account found</div>`;

                            card.innerHTML = `
                                <div class="previous-trip-card__head">
                                    <div class="previous-trip-card__title">
                                        ${escapeHtml(trip.trip_id ?? 'Previous Trip')}
                                    </div>
                                    <div class="previous-trip-card__amount">
                                        Rs ${Number(trip.payment_amount ?? 0).toLocaleString()}
                                    </div>
                                </div>

                                <div class="previous-trip-card__meta">
                                    <div><strong>Route:</strong> ${escapeHtml(trip.pickup_location ?? 'N/A')} → ${escapeHtml(trip.delivery_location ?? 'N/A')}</div>
                                    <div><strong>Type:</strong> ${escapeHtml(formatText(trip.trip_type ?? 'N/A'))}</div>
                                    <div><strong>Driver:</strong> ${escapeHtml(trip.driver_name ?? 'N/A')}</div>
                                    <div><strong>Truck:</strong> ${escapeHtml(trip.truck_number ?? 'N/A')}</div>
                                    <div><strong>Container:</strong> ${escapeHtml(trip.container_number ?? 'N/A')}</div>
                                    <div><strong>Distance:</strong> ${trip.distance_km ?? 0} KM | <strong>ETA:</strong> ${trip.eta_mins ?? 0} mins</div>
                                    <div><strong>Receiver:</strong> ${escapeHtml(trip.delivery_name ?? 'N/A')} | ${escapeHtml(trip.delivery_phone ?? 'N/A')}</div>
                                    <div><strong>Package:</strong> ${escapeHtml(trip.package_description ?? 'N/A')}</div>
                                    ${accountSummary}
                                    <div><strong>Created:</strong> ${escapeHtml(trip.created_at ?? 'N/A')}</div>
                                </div>

                                <div class="previous-trip-actions">
                                    <button type="button" class="use-payment-btn" onclick="usePreviousPayment(${index})">
                                        Use Payment Only
                                    </button>

                                    <button type="button" class="use-all-btn" onclick="usePreviousTripDetails(${index})">
                                        Use All Previous Details
                                    </button>

                                    <button type="button" class="account-preview-btn" onclick="showPreviousAccountDetails(${index})">
                                        View Account Details
                                    </button>
                                </div>
                            `;

                            previousTripsList.appendChild(card);
                        });

                        suggestionBox.style.display = "block";
                    } else {
                        suggestionBox.style.display = "none";
                    }
                })
                .catch(error => {
                    console.error(error);
                    suggestionBox.style.display = "none";
                });
            }, 500);
        }

        function usePreviousPayment(index) {
            const trip = previousTrips[index];

            if (!trip) return;

            setInputValue("payment_amount", trip.payment_amount);
        }

        function usePreviousTripDetails(index) {
            const trip = previousTrips[index];

            if (!trip) return;

            setInputValue("payment_amount", trip.payment_amount);

            setSelectValue("driver_id", trip.driver_id);
            setSelectValue("truck_id", trip.truck_id);
            setSelectValue("container_id", trip.container_id);

            setInputValue("delivery_name", trip.delivery_name);
            setInputValue("delivery_phone", trip.delivery_phone);
            setInputValue("delivery_email", trip.delivery_email);

            setInputValue("package_description", trip.package_description);
            setInputValue("package_weight", trip.package_weight);
            setInputValue("package_height", trip.package_height);
            setInputValue("package_length", trip.package_length);
            setInputValue("package_width", trip.package_width);
        }

        function showPreviousAccountDetails(index) {
            const trip = previousTrips[index];

            if (!trip) return;

            const modal = document.getElementById("previousAccountModal");
            const title = document.getElementById("previousAccountModalTitle");
            const sub = document.getElementById("previousAccountModalSub");
            const body = document.getElementById("previousAccountModalBody");

            title.innerText = "Previous Account Details";
            sub.innerText = "Trip: " + (trip.trip_id ?? "N/A") + " | Route: " + (trip.pickup_location ?? "N/A") + " → " + (trip.delivery_location ?? "N/A");

            if (!trip.account) {
                body.innerHTML = `
                    <div class="previous-account-empty">
                        No account found for this previous trip.
                    </div>
                `;

                modal.classList.add("active");
                document.body.style.overflow = "hidden";
                return;
            }

            const account = trip.account;
            const transactions = account.transactions ?? [];

            let transactionRows = "";

            if (transactions.length > 0) {
                transactions.forEach(transaction => {
                    transactionRows += `
                        <tr>
                            <td>${escapeHtml(transaction.expense_date ?? "N/A")}</td>
                            <td>${escapeHtml(formatText(transaction.type ?? "N/A"))}</td>
                            <td>${escapeHtml(transaction.title ?? "N/A")}</td>
                            <td>
                                ${escapeHtml(transaction.source_name ?? "N/A")}
                                <br>
                                <small>
                                    ${escapeHtml(transaction.source_type ?? "N/A")}
                                    ${transaction.source_id ? "#" + escapeHtml(transaction.source_id) : ""}
                                </small>
                            </td>
                            <td><strong>Rs ${Number(transaction.amount ?? 0).toLocaleString()}</strong></td>
                            <td>Rs ${Number(transaction.balance_before ?? 0).toLocaleString()}</td>
                            <td>Rs ${Number(transaction.balance_after ?? 0).toLocaleString()}</td>
                            <td>${escapeHtml(transaction.description ?? "N/A")}</td>
                        </tr>
                    `;
                });
            }

            body.innerHTML = `
                <div class="previous-account-stats">
                    <div class="previous-account-stat opening">
                        <span>Opening Amount</span>
                        <strong>Rs ${Number(account.opening_amount ?? 0).toLocaleString()}</strong>
                    </div>

                    <div class="previous-account-stat expense">
                        <span>Total Expense</span>
                        <strong>Rs ${Number(account.total_expense ?? 0).toLocaleString()}</strong>
                    </div>

                    <div class="previous-account-stat remaining">
                        <span>Remaining Amount</span>
                        <strong>Rs ${Number(account.remaining_amount ?? 0).toLocaleString()}</strong>
                    </div>
                </div>

                <div class="previous-account-info">
                    <div>
                        <span>Account ID</span>
                        <strong>#${escapeHtml(account.id ?? "N/A")}</strong>
                    </div>

                    <div>
                        <span>Status</span>
                        <strong>${escapeHtml(formatText(account.status ?? "active"))}</strong>
                    </div>

                    <div>
                        <span>Created At</span>
                        <strong>${escapeHtml(account.created_at ?? "N/A")}</strong>
                    </div>

                    <div>
                        <span>Total Transactions</span>
                        <strong>${transactions.length}</strong>
                    </div>
                </div>

                ${
                    transactions.length > 0
                    ? `
                        <div class="previous-account-table-wrap">
                            <table class="previous-account-table">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Source</th>
                                        <th>Amount</th>
                                        <th>Before</th>
                                        <th>After</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    ${transactionRows}
                                </tbody>
                            </table>
                        </div>
                    `
                    : `
                        <div class="previous-account-empty">
                            No transactions found for this previous trip account.
                        </div>
                    `
                }
            `;

            modal.classList.add("active");
            document.body.style.overflow = "hidden";
        }

        function closePreviousAccountModal() {
            const modal = document.getElementById("previousAccountModal");

            modal.classList.remove("active");
            document.body.style.overflow = "";
        }

        document.addEventListener("click", function (event) {
            const modal = document.getElementById("previousAccountModal");

            if (event.target === modal) {
                closePreviousAccountModal();
            }
        });

        document.addEventListener("keydown", function (event) {
            if (event.key === "Escape") {
                closePreviousAccountModal();
            }
        });

        function setInputValue(id, value) {
            const input = document.getElementById(id);

            if (input && value !== null && value !== undefined) {
                input.value = value;
            }
        }

        function setSelectValue(id, value) {
            const select = document.getElementById(id);

            if (!select || value === null || value === undefined) return;

            select.value = value;
        }

        function formatText(value) {
            return String(value).replace(/_/g, " ").replace(/\b\w/g, char => char.toUpperCase());
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        window.onload = initMap;
    </script>
@endsection