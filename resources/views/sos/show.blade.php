@extends('layouts.app')

@section('title', 'SOS Alert Details')
@section('body-class', 'page-dashboard')

@section('content')

@php
    $driver = $sos->driver;
    $trip   = $sos->trip;

    $severity = ucfirst($sos->emergency_type ?? 'warning');
    $status   = ucfirst($sos->status ?? 'pending');

    $raisedAt = \Carbon\Carbon::parse($sos->created_at);
@endphp

<section class="page">

    <a href="{{ route('sos.index') }}" class="detail-back">← Back to SOS Alerts</a>

    <!-- Banner -->
    <div class="sos-banner">
        <div class="sos-banner__icon">⚠</div>
        <div>
            <div>{{ $severity }}</div>
            <div>{{ $sos->description ?? 'No description provided' }}</div>
        </div>
        <div class="sos-banner__eta">
            {{ $raisedAt->diffForHumans() }}
        </div>
    </div>

    <!-- Actions -->
    <div class="sos-respond-bar">
        <div class="sos-respond-bar__msg">
            <strong>{{ $driver->name ?? 'Driver' }}</strong> alert is active.
        </div>

        <form method="POST" action="{{ route('sos.resolve') }}">
            @csrf
            <input type="hidden" name="sos_id" value="{{ $sos->id }}">
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

            <h1>#{{ $sos->id }}</h1>

            <div class="detail-hero__sub">
                <span>👤 {{ $driver->name ?? '-' }}</span>
                <span>🚚 {{ $trip->truck->name ?? '-' }}</span>
                <span>📍 {{ $sos->location ?? '-' }}</span>
                <span>🕒 {{ $raisedAt->format('Y-m-d H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- QUICK STATS -->
    <div class="detail-quickstats">
        <div class="qs">
            <div class="qs__label">Severity</div>
            <div class="qs__value">{{ $severity }}</div>
            <div class="qs__sub">{{ $sos->emergency_type }}</div>
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
                    <p>{{ $sos->location ?? 'No location available' }}</p>
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
                            <span class="info-row__val">{{ $sos->emergency_type }}</span>
                        </div>

                        <div class="info-row">
                            <span class="info-row__key">Driver</span>
                            <span class="info-row__val">{{ $driver->name ?? '-' }}</span>
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
                        <p>{{ $sos->description ?? 'No description provided' }}</p>
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
                    <strong>{{ $driver->name ?? '-' }}</strong>
                    <br>
                    {{ $driver->phone ?? '-' }}
                </div>
            </div>

            <!-- Trip -->
            <div class="card">
                <div class="card__head">
                    <h3>Trip</h3>
                </div>
                <div class="card__body">
                    ID: {{ $trip->id ?? '-' }}
                </div>
            </div>

        </aside>

    </div>

</section>

@endsection