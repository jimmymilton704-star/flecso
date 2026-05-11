@extends('layouts.app')

@section('title', 'SOS Alert Details')
@section('body-class', 'page-dashboard')
<link rel="stylesheet" href="{{ asset('css/trips.css') }}" />
<link rel="stylesheet" href="{{ asset('css/detail.css') }}" />

@section('content')

    {{-- @dd($truckMaintainance,$truckhealth) --}}
    @php
        $driver = $alert->driver;
        $trip = $alert->trip;

        $severity = ucfirst($alert->emergency_type ?? 'warning');
        $status = ucfirst($alert->is_read == 1 ? 'Resolved' : 'Pending');
        $raisedAt = \Carbon\Carbon::parse($alert->created_at);
    @endphp
    <link rel="stylesheet" href="{{ asset('css/sos.css') }}">
    <section class="page">

        <a href="{{ route('sos.index') }}" class="detail-back">← Back to SOS Alerts</a>

        <!-- Banner -->
        <div class="sos-banner">
            <div class="sos-banner__icon"><svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg></div>
            <div>
                <div>{{ ucwords(str_replace('_', ' ', $alert->type)) }}</div>
                <div>{{ $alert->message ?? 'No description provided' }}</div>
            </div>
            <div class="sos-banner__eta">
                {{ $raisedAt->diffForHumans() }}
            </div>
        </div>

        <!-- Actions -->
        <div class="sos-respond-bar">
            <div class="sos-respond-bar__msg">
                <strong>{{ $driver->full_name ?? 'Driver' }}</strong> alert is active.
            </div>

            @if ($alert->is_read == 0)
                <form method="POST" action="{{ route('sos.resolve') }}">
                    @csrf
                    <input type="hidden" name="alert_id" value="{{ $alert->id }}">
                    <input type="hidden" name="source" value="{{ $alert->alert_source }}">
                    <button class="btn btn--primary btn--sm">
                        Mark resolved
                    </button>
                </form>
            @endif
        </div>

        <!-- HERO -->
        <div class="detail-hero">
            <div class="detail-hero__icon"
                style="background:linear-gradient(135deg,var(--danger-50),#FFDCDC);color:var(--danger-700)">
                <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
            </div>
            <div class="detail-hero__body">
                <div class="detail-hero__meta">
                    <span>FLEET ID</span>
                    <span class="badge badge--danger">{{ $severity }}</span>
                    <span class="badge badge--neutral">{{ $status }}</span>
                </div>

                <h1>FLEET - {{ $alert->id }}</h1>

                <div class="detail-hero__sub">
                    <span>👤 {{ $driver->name ?? '-' }}</span>
                    <span>🚚 {{ $trip->truck->name ?? '-' }}</span>
                    <span>📍 {{ $alert->location ?? '-' }}</span>
                    <span>🕒 {{ $raisedAt->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- QUICK STATS -->
        <div class="detail-quickstats">
            <div class="qs">
                <div class="qs__label">Severity</div>
                <div class="qs__value">{{ $severity }}</div>
                <div class="qs__sub">{{ $alert->emergency_type }}</div>
            </div>

            <div class="qs">
                <div class="qs__label">Time Since Raised</div>
                <div class="qs__value">{{ $raisedAt->diffForHumans() }}</div>
                <div class="qs__sub">{{ $raisedAt->format('Y-m-d H:i') }}</div>
            </div>

            <div class="qs">
                <div class="qs__label">Status</div>
                <div class="qs__value">{{ $status }}</div>
                <div class="qs__sub">Current state</div>
            </div>


        </div>

        <div class="detail-grid">

            <!-- LEFT -->
            <div>

                <!-- Location -->
                <div class="card">
                    <div class="card__head">
                        <h3>Truck Details</h3>
                    </div>
                    <div class="card__body">
                        <p>{{ $alert->truck->truck_number ?? 'No truck number available' }}</p>
                    </div>
                </div>

                <!-- HEALTH LOG -->
                <div class="card" style="margin-top:14px">
                    <div class="card__head">
                        <h3>Truck Health Log</h3>
                    </div>

                    <div class="card__body">

                        @if ($truckhealth)
                            <div class="info-grid">

                                <div class="info-row">
                                    <span class="info-row__key">Current KM</span>
                                    <span class="info-row__val">{{ $truckhealth->current_km ?? 'N/A' }}</span>
                                </div>

                                {{-- <div class="info-row">
                                    <span class="info-row__key">Oil Level</span>
                                    <span class="info-row__val">{{ $truckhealth->oil_level ?? 'N/A' }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-row__key">Battery</span>
                                    <span class="info-row__val">{{ $truckhealth->battery_status ?? 'N/A' }}</span>
                                </div> --}}

                                <div class="info-row">
                                    <span class="info-row__key">Recorded At</span>
                                    <span class="info-row__val">
                                        {{ $truckhealth->recorded_at ? \Carbon\Carbon::parse($truckhealth->recorded_at)->format('M d, Y') : 'N/A' }}
                                    </span>
                                </div>

                            </div>
                        @else
                            <p>No health log available for this truck.</p>
                        @endif

                    </div>
                </div>
                <!-- MAINTENANCE -->
                <div class="card" style="margin-top:14px">
                    <div class="card__head">
                        <h3>Truck Maintenance</h3>
                    </div>

                    <div class="card__body">

                        @if ($truckMaintainance)
                            <div class="info-grid">

                                <div class="info-row">
                                    <span class="info-row__key">Type</span>
                                    <span class="info-row__val">{{ $truckMaintainance->type ?? 'N/A' }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-row__key">Status</span>
                                    <span class="info-row__val">{{ $truckMaintainance->status ?? 'N/A' }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-row__key">KM Interval</span>
                                    <span class="info-row__val">{{ $truckMaintainance->next_due_km ?? 'N/A' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Last Maintenance KM</span>
                                    <span class="info-row__val">{{ $truckMaintainance->last_service_km ?? 'N/A' }}</span>
                                </div>

                                <div class="info-row">
                                    <span class="info-row__key">Scheduled Date</span>
                                    <span class="info-row__val">
                                        {{ $truckMaintainance->scheduled_date
                                            ? \Carbon\Carbon::parse($truckMaintainance->scheduled_date)->format('M d, Y')
                                            : 'N/A' }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Completed Date</span>
                                    <span class="info-row__val">
                                        {{ $truckMaintainance->completed_date
                                            ? \Carbon\Carbon::parse($truckMaintainance->completed_date)->format('M d, Y')
                                            : 'N/A' }}
                                    </span>
                                </div>

                            </div>
                        @else
                            <p>No maintenance record found.</p>
                        @endif

                    </div>
                </div>

            </div>

            <!-- RIGHT -->
            <aside class="detail-side">

                <!-- DRIVER (through truck) -->
                <div class="card">
                    <div class="card__head">
                        <h3>Driver</h3>
                    </div>

                    <div class="card__body">

                        @if ($alert->truck?->driver)
                            <div class="assignee" style="background:transparent;padding:0;margin-bottom:10px">

                                <img src="{{ asset($alert->truck->driver->avatar ?? 'https://i.pravatar.cc/80') }}">

                                <div>
                                    <div class="assignee__name">
                                        {{ $alert->truck->driver->full_name }}
                                    </div>

                                    <div class="assignee__sub">
                                        {{ $alert->truck->driver->phone ?? '-' }}
                                    </div>
                                </div>

                            </div>

                            <a href="{{ route('drivers.show', $alert->truck->driver->id) }}"
                                class="btn btn--sm btn--ghost btn--block">
                                View profile
                            </a>
                        @else
                            <p>No driver assigned</p>
                        @endif

                    </div>
                </div>

                <!-- TRIP -->
                <div class="card">
                    <div class="card__head">
                        <h3>Trip</h3>
                    </div>

                    <div class="card__body">

                        ID: {{ $trip->id ?? '-' }}

                        @if ($trip)
                            <a href="{{ route('trips.show', $trip->id) }}" class="btn btn--sm btn--ghost btn--block">
                                View Trip
                            </a>
                        @endif

                    </div>
                </div>

            </aside>



        </div>

    </section>

@endsection
