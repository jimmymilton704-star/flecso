@extends('layouts.app')

@section('title', 'View Trip')
@section('body-class', 'page-dashboard')
<link rel="stylesheet" href="{{ asset('css/trips.css') }}" />
<link rel="stylesheet" href="{{ asset('css/detail.css') }}" />

@section('content')

    <section class="page">

        {{-- BACK --}}
        <a href="{{ route('trips.index') }}" class="detail-back">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                <path d="m8 13 4-4 4 4-4 4z"></path>
                <path d="M12 2v4M5 7l2.5 2.5M19 7l-2.5 2.5M12 22a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z"></path>
            </svg>
            Back to Trips
        </a>

        {{-- HERO --}}
        <div class="detail-hero">

            <div class="detail-hero__icon">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="6" cy="19" r="3"></circle>
                    <circle cx="18" cy="5" r="3"></circle>
                    <path d="M6 16V8a4 4 0 0 1 4-4h4M18 8v8a4 4 0 0 1-4 4h-4"></path>
                </svg>
            </div>

            <div class="detail-hero__body">

                <div class="detail-hero__meta">
                    <span class="detail-hero__id">{{ $trip->trip_id }}</span>

                    <span
                        class="badge
                    @if ($trip->trip_status == 'completed') badge--success
                    @elseif($trip->trip_status == 'in_transit') badge--warning
                    @elseif($trip->trip_status == 'cancelled') badge--danger
                    @else badge--neutral @endif">
                        {{ ucfirst(str_replace('_', ' ', $trip->trip_status)) }}
                    </span>

                    <span class="badge badge--orange">{{ ucfirst($trip->trip_type) }}</span>
                </div>

                <h1>{{ $trip->pickup_location }} → {{ $trip->delivery_location }}</h1>

                <div class="detail-hero__sub">
                    <span><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                            <path d="M16 2v4M8 2v4M3 10h18"></path>
                        </svg> {{ $trip->schedule_datetime ?? 'N/A' }}</span>
                    <span><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="6" cy="19" r="3"></circle>
                            <circle cx="18" cy="5" r="3"></circle>
                            <path d="M6 16V8a4 4 0 0 1 4-4h4M18 8v8a4 4 0 0 1-4 4h-4"></path>
                        </svg> {{ $trip->distance_km ?? 0 }} km</span>
                    <span><svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="8" r="4"></circle>
                            <path d="M4 21c0-4 4-7 8-7s8 3 8 7"></path>
                        </svg> {{ $trip->payment_amount ?? 0 }}</span>
                </div>

            </div>

            <div class="detail-hero__actions">

                <a href="{{ route('trips.edit', $trip->id) }}" class="btn btn--ghost"><svg viewBox="0 0 24 24"
                        width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z"></path>
                    </svg>Edit</a>

                <button class="btn btn--primary"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>Notify Driver</button>

            </div>

        </div>

        {{-- QUICK STATS --}}
        <div class="detail-quickstats">

            <div class="qs">
                <div class="qs__label"><svg viewBox="0 0 24 24" width="12" height="12" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="6" cy="19" r="3"></circle>
                        <circle cx="18" cy="5" r="3"></circle>
                        <path d="M6 16V8a4 4 0 0 1 4-4h4M18 8v8a4 4 0 0 1-4 4h-4"></path>
                    </svg>Distance</div>
                <div class="qs__value">{{ $trip->distance_km ?? 0 }} km</div>
            </div>

            <div class="qs">
                <div class="qs__label"><svg viewBox="0 0 24 24" width="12" height="12" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        <path d="M16 2v4M8 2v4M3 10h18"></path>
                    </svg>ETA</div>
                <div class="qs__value">{{ $trip->eta_mins ?? 0 }} min</div>
            </div>

            <div class="qs">
                <div class="qs__label"><svg viewBox="0 0 24 24" width="12" height="12" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 6 9 17l-5-5"></path>
                    </svg>Payment</div>
                <div class="qs__value">Rs {{ $trip->payment_amount ?? 0 }}</div>
            </div>

            <div class="qs">
                <div class="qs__label"><svg viewBox="0 0 24 24" width="12" height="12" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="7" width="18" height="11" rx="1.5"></rect>
                        <path d="M7 7v11M12 7v11M17 7v11"></path>
                    </svg>Trip Type</div>
                <div class="qs__value">{{ ucfirst($trip->trip_type) }}</div>
            </div>

        </div>

        {{-- ROUTE --}}
        <div class="route-display">

            <div class="route-display__stop">
                <div class="route-display__pin route-display__pin--from">A</div>
                <div>
                    <div class="route-display__label">Pickup</div>
                    <div class="route-display__place">{{ $trip->pickup_location }}</div>
                </div>
            </div>

            <div class="route-display__line"></div>

            <div class="route-display__stop">
                <div class="route-display__pin route-display__pin--to">B</div>
                <div>
                    <div class="route-display__label">Delivery</div>
                    <div class="route-display__place">{{ $trip->delivery_location }}</div>
                </div>
            </div>

        </div>

        {{-- MAIN GRID --}}
        <div class="detail-grid">

            {{-- LEFT --}}
            <div>

                {{-- MAP --}}
                <div class="card">
                    <div class="card__head">
                        <h3>Route Map</h3>
                    </div>

                    <div class="card__body" style="padding:0">
                        <div id="map" style="height:420px;"></div>
                    </div>
                </div>

                {{-- BASIC INFO --}}
                <div class="card" style="margin-top:14px">
                    <div class="card__head">
                        <h3>Basic Details</h3>
                    </div>
                    <div class="card__body">
                        <div class="info-grid">

                            <div class="info-row">
                                <span>Trip ID</span>
                                <span>{{ $trip->trip_id }}</span>
                            </div>

                            <div class="info-row">
                                <span>Status</span>
                                <span>{{ ucfirst($trip->trip_status) }}</span>
                            </div>

                            <div class="info-row">
                                <span>Trip Type</span>
                                <span>{{ $trip->trip_type }}</span>
                            </div>

                            <div class="info-row">
                                <span>Schedule</span>
                                <span>{{ $trip->schedule_datetime ?? 'N/A' }}</span>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- PACKAGE --}}
                <div class="card" style="margin-top:14px">
                    <div class="card__head">
                        <h3>Package Information</h3>
                    </div>
                    <div class="card__body">
                        <div class="info-grid">

                            <div class="info-row">
                                <span>Description</span>
                                <span>{{ $trip->package_description ?? '-' }}</span>
                            </div>

                            <div class="info-row">
                                <span>Weight</span>
                                <span>{{ $trip->package_weight ?? 0 }} kg</span>
                            </div>

                            <div class="info-row">
                                <span>Dimensions</span>
                                <span>
                                    {{ $trip->package_height ?? 0 }} ×
                                    {{ $trip->package_length ?? 0 }} ×
                                    {{ $trip->package_width ?? 0 }}
                                </span>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- DELIVERY --}}
                <div class="card" style="margin-top:14px">
                    <div class="card__head">
                        <h3>Delivery Details</h3>
                    </div>
                    <div class="card__body">
                        <div class="info-grid">

                            <div class="info-row">
                                <span>Name</span>
                                <span>{{ $trip->delivery_name ?? '-' }}</span>
                            </div>

                            <div class="info-row">
                                <span>Phone</span>
                                <span>{{ $trip->delivery_phone ?? '-' }}</span>
                            </div>

                            <div class="info-row">
                                <span>Email</span>
                                <span>{{ $trip->delivery_email ?? '-' }}</span>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            {{-- RIGHT SIDEBAR --}}
            <aside class="detail-side">

                {{-- DRIVER --}}
                <div class="card">
                    <div class="card__head">
                        <h3>Driver</h3>
                    </div>
                    <div class="card__body">

                        @if ($trip->driver)
                            <div class="assignee" style="background:transparent;padding:0;margin-bottom:10px">
                                <img src="{{ asset($trip->driver->avatar ?? 'https://i.pravatar.cc/80') }}">
                                <div>
                                    <div class="assignee__name">{{ $trip->driver->name }}</div>
                                    <div class="assignee__sub">{{ $trip->driver->phone }}</div>

                                </div>

                            </div>
                            <a href="{{ route('drivers.show', $trip->driver->id) }}"
                                class="btn btn--sm btn--ghost btn--block">View profile</a>
                        @else
                            <p>No driver assigned</p>
                        @endif

                    </div>
                </div>

                {{-- TRUCK --}}
                <div class="card">
                    <div class="card__head">
                        <h3>Truck</h3>
                    </div>
                    <div class="card__body">

                        @if ($trip->truck)
                            <div class="assignee" style="background:transparent;padding:0;margin-bottom:10px">
                                <img src="{{ asset($trip->truck->image ?? 'https://i.pravatar.cc/80') }}">
                                <div>
                                    <div class="assignee__name">{{ $trip->truck->truck_number }}</div>
                                </div>
                            </div>
                            <a href="{{ route('trucks.show', $trip->truck->id) }}"
                                class="btn btn--sm btn--ghost btn--block">View truck</a>
                        @else
                            <p>No truck assigned</p>
                        @endif

                    </div>
                </div>

                {{-- CONTAINER --}}
                <div class="card">
                    <div class="card__head">
                        <h3>Container</h3>
                    </div>
                    <div class="card__body">

                        @if ($trip->container)
                            <div class="assignee" style="background:transparent;padding:0;margin-bottom:10px">
                                <img src="{{ asset($trip->container->image ?? 'https://i.pravatar.cc/80') }}">
                                <div>
                                    <div class="assignee__name">{{ $trip->container->container_license_number }}</div>
                                </div>
                            </div>
                            <a href="{{ route('containers.show', $trip->container->id) }}"
                                class="btn btn--sm btn--ghost btn--block">View container</a>
                        @else
                            <p>No container assigned</p>
                        @endif

                    </div>
                </div>

            </aside>

        </div>

    </section>

    {{-- GOOGLE MAPS --}}
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDQqP59sFi_cXyk8Afq_AY4Dkg4DCf-xj0&libraries=places">
    </script>


    <script>
        function initMap() {

            const pickup = {
                lat: parseFloat("{{ $trip->pickup_lat ?? 24.8607 }}"),
                lng: parseFloat("{{ $trip->pickup_lng ?? 67.0011 }}")
            };

            const delivery = {
                lat: parseFloat("{{ $trip->delivery_lat ?? 24.8607 }}"),
                lng: parseFloat("{{ $trip->delivery_lng ?? 67.0011 }}")
            };

            const map = new google.maps.Map(document.getElementById("map"), {
                center: pickup,
                zoom: 6
            });

            const directionsService = new google.maps.DirectionsService();
            const directionsRenderer = new google.maps.DirectionsRenderer({
                map: map,
                suppressMarkers: false
            });

            directionsService.route({
                origin: pickup,
                destination: delivery,
                travelMode: "DRIVING"
            }, function(response, status) {
                if (status === "OK") {
                    directionsRenderer.setDirections(response);
                }
            });

            // DRIVER LIVE MARKER (optional future GPS)
            @if ($trip->driver && $trip->driver->lat && $trip->driver->lng)
                new google.maps.Marker({
                    position: {
                        lat: {{ $trip->driver->lat }},
                        lng: {{ $trip->driver->lng }}
                    },
                    map: map,
                    title: "Driver Location",
                    icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                });
            @endif
        }

        window.onload = initMap;
    </script>

@endsection
