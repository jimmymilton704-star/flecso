@extends('layouts.app')

@section('title', 'SOS Alert Details')
@section('body-class', 'page-dashboard')
<link rel="stylesheet" href="{{ asset('css/trips.css') }}" />
<link rel="stylesheet" href="{{ asset('css/detail.css') }}" />

@section('content')

@php
    $driver = $alert->driver;
    $trip   = $alert->trip;

    $severity = ucfirst($alert->emergency_type ?? 'warning');
    $status   = ucfirst($alert->status ? 'Resolved' : 'Pending');

    $raisedAt = \Carbon\Carbon::parse($alert->created_at);
@endphp
@dd($status)
<link rel="stylesheet" href="{{ asset('css/sos.css') }}">

<section class="page">

    <a href="{{ route('sos.index') }}" class="detail-back">← Back to SOS Alerts</a>

    <!-- Banner -->
    <div class="sos-banner">
        <div class="sos-banner__icon">⚠</div>
        <div>
            <div>{{ $severity }}</div>
            <div>{{ $alert->description ?? 'No description provided' }}</div>
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

        <form method="POST" action="{{ route('sos.resolve') }}">
            @csrf
            <input type="hidden" name="sos_id" value="{{ $alert->id }}">
            <button class="btn btn--primary btn--sm">
                Mark resolved
            </button>
        </form>
    </div>

    <!-- HERO -->
    <div class="detail-hero">
        <div class="detail-hero__body">
            <div class="detail-hero__meta">
                <span>SOS ID</span>
                <span class="badge badge--danger">{{ $severity }}</span>
                <span class="badge badge--neutral">{{ $status }}</span>
            </div>

            <h1>#{{ $alert->id }}</h1>

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

        <div class="qs">
            <div class="qs__label">Trip ID</div>
            <div class="qs__value">
                {{ $trip->id ?? '-' }}
            </div>
            <div class="qs__sub">Linked trip</div>
        </div>
    </div>

    <div class="detail-grid">

        <!-- LEFT -->
        <div>

            <!-- Location -->
            <div class="card">
                <div class="card__head">
                    <h3>Incident Location</h3>
                </div>
                <div class="card__body">
                    <p>{{ $alert->location ?? 'No location available' }}</p>
                </div>
            </div>

            <!-- Details -->
            <div class="card" style="margin-top:14px">
                <div class="card__head">
                    <h3>Incident Details</h3>
                </div>
                <div class="card__body">

                    <div class="info-grid">

                        <div class="info-row">
                            <span class="info-row__key">Type</span>
                            <span class="info-row__val">{{ $alert->emergency_type }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-row__key">Driver</span>
                            <span class="info-row__val">{{ $driver->full_name ?? '-' }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-row__key">Raised At</span>
                            <span class="info-row__val">{{ $raisedAt->format('Y-m-d H:i') }}</span>
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
                                    <div class="assignee__name">{{ $trip->driver->full_name }}</div>
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

            <!-- Trip -->
            <div class="card">
                <div class="card__head">
                    <h3>Trip</h3>
                </div>
                <div class="card__body">
                    ID: {{ $trip->id ?? '-' }}

                        <a href="{{ route('trips.show', $trip->id) }}"
                                class="btn btn--sm btn--ghost btn--block">View Trip</a>
                </div>
             
            </div>

        </aside>

    </div>

</section>

@endsection