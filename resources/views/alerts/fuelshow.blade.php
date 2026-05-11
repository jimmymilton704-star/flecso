@extends('layouts.app')

@section('title', 'Fuel Alert Details')
@section('body-class', 'page-dashboard')

<link rel="stylesheet" href="{{ asset('css/trips.css') }}" />
<link rel="stylesheet" href="{{ asset('css/detail.css') }}" />
<link rel="stylesheet" href="{{ asset('css/sos.css') }}">

@section('content')

    @php
        $driver = $alert->driver;
        $truck = $alert->truck;
        $trip = $alert->trip;

        $status = ucfirst($alert->is_resolved == 1 ? 'Resolved' : 'Pending');
        $raisedAt = \Carbon\Carbon::parse($alert->created_at);
    @endphp

    <section class="page">

        <a href="{{ route('sos.index') }}" class="detail-back">← Back to Fuel Alerts</a>

        <!-- Banner -->
        <div class="sos-banner">
            <div class="sos-banner__icon">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 20h20L12 2z"></path>
                    <path d="M12 9v4"></path>
                    <path d="M12 17h.01"></path>
                </svg>
            </div>

            <div>
                <div>{{ ucwords(str_replace('_', ' ', $alert->alert_type ?? 'Fuel Alert')) }}</div>
                <div>{{ $alert->message ?? 'No description provided' }}</div>
            </div>

            <div class="sos-banner__eta">
                {{ $raisedAt->diffForHumans() }}
            </div>
        </div>

        <!-- Actions -->
        <div class="sos-respond-bar">
            <div class="sos-respond-bar__msg">
                <strong>{{ $driver->full_name ?? 'Driver' }}</strong> fuel alert is active.
            </div>

            @if (($alert->is_resolved ?? 0) == 0)
                <form method="POST" action="{{ route('sos.resolve') }}">
                    @csrf
                    <input type="hidden" name="alert_id" value="{{ $alert->id }}">
                    <input type="hidden" name="source" value="{{ $alert->alert_source ?? 'fuel' }}">
                    <button class="btn btn--primary btn--sm">
                        Mark resolved
                    </button>
                </form>
            @endif
        </div>

        <!-- HERO -->
        <div class="detail-hero">
            <div class="detail-hero__icon"
                style="background:linear-gradient(135deg,#FFF7ED,#FFEDD5);color:#C2410C">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2L2 20h20L12 2z"></path>
                    <path d="M12 9v4"></path>
                    <path d="M12 17h.01"></path>
                </svg>
            </div>

            <div class="detail-hero__body">
                <div class="detail-hero__meta">
                    <span>FUEL ID</span>
                    <span class="badge badge--danger">{{ ucfirst($alert->alert_type ?? 'Warning') }}</span>
                    <span class="badge badge--neutral">{{ $status }}</span>
                </div>

                <h1>FUEL - {{ $alert->id }}</h1>

                <div class="detail-hero__sub">
                    <span>👤 {{ $driver->full_name ?? '-' }}</span>
                    <span>🚚 {{ $truck->truck_number ?? '-' }}</span>
                    <span>📍 {{ $alert->location ?? '-' }}</span>
                    <span>🕒 {{ $raisedAt->format('M d, Y h:i A') }}</span>
                </div>
            </div>
        </div>

        <!-- QUICK STATS -->
        <div class="detail-quickstats">
            <div class="qs">
                <div class="qs__label">Type</div>
                <div class="qs__value">{{ ucfirst($alert->alert_type ?? 'Fuel Alert') }}</div>
                <div class="qs__sub">Alert category</div>
            </div>

            <div class="qs">
                <div class="qs__label">Time Since Raised</div>
                <div class="qs__value">{{ $raisedAt->diffForHumans() }}</div>
                <div class="qs__sub">{{ $raisedAt->format('M d, Y h:i A') }}</div>
            </div>

            <div class="qs">
                <div class="qs__label">Status</div>
                <div class="qs__value">{{ $status }}</div>
                <div class="qs__sub">Current state</div>
            </div>

            <div class="qs">
                <div class="qs__label">Fuel Log</div>
                <div class="qs__value">{{ $fuellog ? 'Available' : 'Missing' }}</div>
                <div class="qs__sub">Latest log record</div>
            </div>
        </div>

        <div class="detail-grid">

            <!-- LEFT -->
            <div>

                <!-- Truck -->
                <div class="card">
                    <div class="card__head">
                        <h3>Truck Details</h3>
                    </div>
                    <div class="card__body">
                        <p>{{ $truck->truck_number ?? 'No truck number available' }}</p>
                    </div>
                </div>

                <!-- Fuel Log -->
                <div class="card" style="margin-top:14px">
                    <div class="card__head">
                        <h3>Fuel Log</h3>
                    </div>

                    <div class="card__body">
                        @if ($fuellog)
                            <div class="info-grid">

                                <div class="info-row">
                                    <span class="info-row__key">Fuel Liters</span>
                                    <span class="info-row__val">{{ $fuellog->fuel_liters ?? 'N/A' }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-row__key">Price / Liter</span>
                                    <span class="info-row__val">{{ $fuellog->fuel_price_per_liter ?? 'N/A' }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-row__key">Total Cost</span>
                                    <span class="info-row__val">{{ $fuellog->total_cost ?? 'N/A' }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-row__key">Fuel Station</span>
                                    <span class="info-row__val">{{ $fuellog->fuel_station ?? 'N/A' }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-row__key">Odometer</span>
                                    <span class="info-row__val">{{ $fuellog->odometer_reading ?? 'N/A' }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-row__key">Refuel Time</span>
                                    <span class="info-row__val">
                                        {{ $fuellog->refuel_time ? \Carbon\Carbon::parse($fuellog->refuel_time)->format('M d, Y h:i A') : 'N/A' }}
                                    </span>
                                </div>

                                <div class="info-row">
                                    <span class="info-row__key">Recorded At</span>
                                    <span class="info-row__val">
                                        {{ $fuellog->created_at ? \Carbon\Carbon::parse($fuellog->created_at)->format('M d, Y h:i A') : 'N/A' }}
                                    </span>
                                </div>

                            </div>
                        @else
                            <p>No fuel log available for this truck.</p>
                        @endif
                    </div>
                </div>

                <!-- Alert Details -->
                <div class="card" style="margin-top:14px">
                    <div class="card__head">
                        <h3>Alert Details</h3>
                    </div>

                    <div class="card__body">
                        <div class="info-grid">

                            <div class="info-row">
                                <span class="info-row__key">Driver</span>
                                <span class="info-row__val">{{ $driver->full_name ?? '-' }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Truck</span>
                                <span class="info-row__val">{{ $truck->truck_number ?? '-' }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Raised At</span>
                                <span class="info-row__val">{{ $raisedAt->format('M d, Y h:i A') }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Status</span>
                                <span class="info-row__val">{{ $status }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Trip</span>
                                <span class="info-row__val">{{ $trip->id ?? '-' }}</span>
                            </div>

                        </div>

                        <div style="margin-top:14px">
                            <h5>Description</h5>
                            <p>{{ $alert->description ?? 'No description provided' }}</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT -->
            <aside class="detail-side">

                <!-- Driver -->
                <div class="card">
                    <div class="card__head">
                        <h3>Driver</h3>
                    </div>
                    <div class="card__body">

                        @if ($driver)
                            <div class="assignee" style="background:transparent;padding:0;margin-bottom:10px">
                                <img src="{{ asset($driver->avatar ?? 'https://i.pravatar.cc/80') }}">
                                <div>
                                    <div class="assignee__name">{{ $driver->full_name ?? 'N/A' }}</div>
                                    <div class="assignee__sub">{{ $driver->phone ?? '-' }}</div>
                                </div>
                            </div>

                            <a href="{{ route('drivers.show', $driver->id) }}"
                               class="btn btn--sm btn--ghost btn--block">
                                View profile
                            </a>
                        @else
                            <p>No driver assigned</p>
                        @endif

                    </div>
                </div>

                <!-- Trip -->
                <div class="card" style="margin-top:14px">
                    <div class="card__head">
                        <h3>Trip</h3>
                    </div>
                    <div class="card__body">
                        ID: {{ $trip->id ?? '-' }}

                        @if ($trip)
                            <a href="{{ route('trips.show', $trip->id) }}"
                               class="btn btn--sm btn--ghost btn--block"
                               style="margin-top:10px;">
                                View Trip
                            </a>
                        @endif
                    </div>
                </div>

            </aside>

        </div>

    </section>

@endsection