@extends('layouts.app')

@section('title', 'View Trip')
@section('body-class', 'page-dashboard')

<link rel="stylesheet" href="{{ asset('css/trips.css') }}" />
<link rel="stylesheet" href="{{ asset('css/detail.css') }}" />

@section('content')

@php
    $account = $trip->account;

    $openingAmount = $account ? (float) $account->opening_amount : 0;
    $totalExpense = $account ? (float) $account->total_expense : 0;
    $remainingAmount = $account ? (float) $account->remaining_amount : 0;

    $usedPercent = $openingAmount > 0
        ? min(100, round(($totalExpense / $openingAmount) * 100))
        : 0;

    $barClass = $usedPercent >= 85
        ? 'danger'
        : ($usedPercent >= 60 ? 'warning' : '');
@endphp

<section class="page">

    <style>
        .account-summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-bottom: 16px;
        }

        .account-summary-card {
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 16px;
            background: #f8fafc;
        }

        .account-summary-card.blue {
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .account-summary-card.orange {
            background: #fff7ed;
            border-color: #fed7aa;
        }

        .account-summary-card.green {
            background: #ecfdf5;
            border-color: #bbf7d0;
        }

        .account-summary-card.red {
            background: #fef2f2;
            border-color: #fecaca;
        }

        .account-summary-card__label {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .account-summary-card__value {
            font-size: 22px;
            font-weight: 900;
            color: #0f172a;
        }

        .account-progress {
            width: 100%;
            height: 10px;
            background: #e5e7eb;
            border-radius: 999px;
            overflow: hidden;
            margin: 12px 0 6px;
        }

        .account-progress__bar {
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #16a34a, #22c55e);
        }

        .account-progress__bar.warning {
            background: linear-gradient(90deg, #f59e0b, #f97316);
        }

        .account-progress__bar.danger {
            background: linear-gradient(90deg, #dc2626, #ef4444);
        }

        .account-progress-meta {
            display: flex;
            justify-content: space-between;
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
        }

        .account-mini-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .account-mini-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #eef2f7;
            font-size: 13px;
        }

        .account-mini-row:last-child {
            border-bottom: 0;
        }

        .account-mini-row span {
            color: #64748b;
        }

        .account-mini-row strong {
            color: #0f172a;
        }

        .tx-table-wrap {
            overflow-x: auto;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
        }

        .tx-table {
            width: 100%;
            min-width: 920px;
            border-collapse: collapse;
        }

        .tx-table th {
            background: #f8fafc;
            color: #475569;
            font-size: 12px;
            text-align: left;
            padding: 13px 14px;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
            text-transform: uppercase;
            letter-spacing: .03em;
        }

        .tx-table td {
            padding: 14px;
            border-bottom: 1px solid #eef2f7;
            font-size: 13px;
            color: #334155;
            vertical-align: top;
        }

        .tx-table tr:last-child td {
            border-bottom: 0;
        }

        .tx-type {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 999px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
        }

        .tx-source {
            font-weight: 800;
            color: #0f172a;
        }

        .tx-source small {
            display: block;
            color: #64748b;
            font-weight: 600;
            margin-top: 4px;
        }

        .tx-amount {
            font-weight: 900;
            color: #0f172a;
            white-space: nowrap;
        }

        .tx-balance {
            white-space: nowrap;
            color: #475569;
        }

        .empty-state {
            text-align: center;
            padding: 34px 18px;
            color: #64748b;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 16px;
        }

        .empty-state strong {
            display: block;
            color: #0f172a;
            margin-bottom: 6px;
            font-size: 15px;
        }

        @media (max-width: 900px) {
            .account-summary-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

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
            <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="6" cy="19" r="3"></circle>
                <circle cx="18" cy="5" r="3"></circle>
                <path d="M6 16V8a4 4 0 0 1 4-4h4M18 8v8a4 4 0 0 1-4 4h-4"></path>
            </svg>
        </div>

        <div class="detail-hero__body">

            <div class="detail-hero__meta">
                <span class="detail-hero__id">{{ $trip->trip_id }}</span>

                <span class="badge
                    @if ($trip->trip_status == 'completed') badge--success
                    @elseif($trip->trip_status == 'in_transit') badge--warning
                    @elseif($trip->trip_status == 'cancelled') badge--danger
                    @else badge--neutral @endif">
                    {{ ucfirst(str_replace('_', ' ', $trip->trip_status)) }}
                </span>

                <span class="badge badge--orange">{{ ucfirst(str_replace('_', ' ', $trip->trip_type)) }}</span>

                @if($account)
                    <span class="badge badge--success">Account Active</span>
                @else
                    <span class="badge badge--danger">No Account</span>
                @endif
            </div>

            <h1>{{ $trip->pickup_location }} → {{ $trip->delivery_location }}</h1>

            <div class="detail-hero__sub">
                <span>
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        <path d="M16 2v4M8 2v4M3 10h18"></path>
                    </svg>
                    {{ $trip->schedule_datetime ?? 'N/A' }}
                </span>

                <span>
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="6" cy="19" r="3"></circle>
                        <circle cx="18" cy="5" r="3"></circle>
                        <path d="M6 16V8a4 4 0 0 1 4-4h4M18 8v8a4 4 0 0 1-4 4h-4"></path>
                    </svg>
                    {{ $trip->distance_km ?? 0 }} km
                </span>

                <span>
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M4 21c0-4 4-7 8-7s8 3 8 7"></path>
                    </svg>
                    Rs {{ number_format($trip->payment_amount ?? 0, 2) }}
                </span>
            </div>

        </div>

        <div class="detail-hero__actions">

            <a href="{{ route('trips.edit', $trip->id) }}" class="btn btn--ghost">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                    <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z"></path>
                </svg>
                Edit
            </a>

            <button class="btn btn--primary">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0"></path>
                </svg>
                Notify Driver
            </button>

        </div>

    </div>

    {{-- QUICK STATS --}}
    <div class="detail-quickstats">

        <div class="qs">
            <div class="qs__label">
                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="6" cy="19" r="3"></circle>
                    <circle cx="18" cy="5" r="3"></circle>
                    <path d="M6 16V8a4 4 0 0 1 4-4h4M18 8v8a4 4 0 0 1-4 4h-4"></path>
                </svg>
                Distance
            </div>
            <div class="qs__value">{{ $trip->distance_km ?? 0 }} km</div>
        </div>

        <div class="qs">
            <div class="qs__label">
                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                    <path d="M16 2v4M8 2v4M3 10h18"></path>
                </svg>
                ETA
            </div>
            <div class="qs__value">{{ $trip->eta_mins ?? 0 }} min</div>
        </div>

        <div class="qs">
            <div class="qs__label">
                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 6 9 17l-5-5"></path>
                </svg>
                Payment
            </div>
            <div class="qs__value">Rs {{ number_format($trip->payment_amount ?? 0, 2) }}</div>
        </div>

        <div class="qs">
            <div class="qs__label">
                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="7" width="18" height="11" rx="1.5"></rect>
                    <path d="M7 7v11M12 7v11M17 7v11"></path>
                </svg>
                Trip Type
            </div>
            <div class="qs__value">{{ ucfirst(str_replace('_', ' ', $trip->trip_type)) }}</div>
        </div>

    </div>

    {{-- ACCOUNT SUMMARY --}}
    <div class="card" style="margin-top:14px">
        <div class="card__head">
            <h3>Trip Account Overview</h3>
        </div>

        <div class="card__body">
            @if($account)

                <div class="account-summary-grid">
                    <div class="account-summary-card blue">
                        <div class="account-summary-card__label">Opening Amount</div>
                        <div class="account-summary-card__value">Rs {{ number_format($openingAmount, 2) }}</div>
                    </div>

                    <div class="account-summary-card orange">
                        <div class="account-summary-card__label">Total Expense</div>
                        <div class="account-summary-card__value">Rs {{ number_format($totalExpense, 2) }}</div>
                    </div>

                    <div class="account-summary-card {{ $remainingAmount <= 0 ? 'red' : 'green' }}">
                        <div class="account-summary-card__label">Remaining Amount</div>
                        <div class="account-summary-card__value">Rs {{ number_format($remainingAmount, 2) }}</div>
                    </div>
                </div>

                <div class="account-progress">
                    <div class="account-progress__bar {{ $barClass }}" style="width: {{ $usedPercent }}%;"></div>
                </div>

                <div class="account-progress-meta">
                    <span>{{ $usedPercent }}% budget used</span>
                    <span>{{ $account->transactions->count() }} transactions</span>
                </div>

            @else
                <div class="empty-state">
                    <strong>No account found</strong>
                    This trip does not have an account yet.
                </div>
            @endif
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

            {{-- ACCOUNT TRANSACTIONS --}}
            <div class="card" style="margin-top:14px">
                <div class="card__head">
                    <h3>Account Transactions</h3>
                </div>

                <div class="card__body">
                    @if($account && $account->transactions->count() > 0)

                        <div class="tx-table-wrap">
                            <table class="tx-table">
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
                                    @foreach($account->transactions as $transaction)
                                        <tr>
                                            <td>
                                                @if($transaction->expense_date)
                                                    {{ \Carbon\Carbon::parse($transaction->expense_date)->format('d M Y') }}
                                                @else
                                                    {{ optional($transaction->created_at)->format('d M Y') }}
                                                @endif
                                            </td>

                                            <td>
                                                <span class="tx-type">
                                                    {{ ucfirst(str_replace('_', ' ', $transaction->type)) }}
                                                </span>
                                            </td>

                                            <td>
                                                <strong>{{ $transaction->title ?? 'N/A' }}</strong>
                                            </td>

                                            <td>
                                                <div class="tx-source">
                                                    {{ $transaction->source_name ?? 'N/A' }}
                                                    <small>
                                                        {{ $transaction->source_type ?? 'N/A' }}
                                                        @if($transaction->source_id)
                                                            #{{ $transaction->source_id }}
                                                        @endif
                                                    </small>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="tx-amount">
                                                    Rs {{ number_format($transaction->amount ?? 0, 2) }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="tx-balance">
                                                    Rs {{ number_format($transaction->balance_before ?? 0, 2) }}
                                                </span>
                                            </td>

                                            <td>
                                                <span class="tx-balance">
                                                    Rs {{ number_format($transaction->balance_after ?? 0, 2) }}
                                                </span>
                                            </td>

                                            <td>
                                                {{ $transaction->description ?? 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    @else
                        <div class="empty-state">
                            <strong>No transactions found</strong>
                            This trip account does not have any expense transaction yet.
                        </div>
                    @endif
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
                            <span>{{ ucfirst(str_replace('_', ' ', $trip->trip_status)) }}</span>
                        </div>

                        <div class="info-row">
                            <span>Trip Type</span>
                            <span>{{ ucfirst(str_replace('_', ' ', $trip->trip_type)) }}</span>
                        </div>

                        <div class="info-row">
                            <span>Schedule</span>
                            <span>{{ $trip->schedule_datetime ?? 'N/A' }}</span>
                        </div>

                        <div class="info-row">
                            <span>Fuel Cost</span>
                            <span>Rs {{ number_format($trip->total_fuel_cost ?? 0, 2) }}</span>
                        </div>

                        <div class="info-row">
                            <span>Total Fuel</span>
                            <span>{{ number_format($trip->total_fuel_liters ?? 0, 2) }} L</span>
                        </div>

                        <div class="info-row">
                            <span>Fuel Cost / KM</span>
                            <span>Rs {{ number_format($trip->fuel_cost_per_km ?? 0, 2) }}</span>
                        </div>

                        <div class="info-row">
                            <span>Average KMPL</span>
                            <span>{{ number_format($trip->avg_kmpl ?? 0, 2) }}</span>
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

            {{-- ACCOUNT DETAIL --}}
            <div class="card">
                <div class="card__head">
                    <h3>Account Detail</h3>
                </div>

                <div class="card__body">
                    @if($account)

                        <div class="account-mini-list">

                            <div class="account-mini-row">
                                <span>Account ID</span>
                                <strong>#{{ $account->id }}</strong>
                            </div>

                            <div class="account-mini-row">
                                <span>Status</span>
                                <strong>{{ ucfirst($account->status ?? 'active') }}</strong>
                            </div>

                            <div class="account-mini-row">
                                <span>Opening</span>
                                <strong>Rs {{ number_format($openingAmount, 2) }}</strong>
                            </div>

                            <div class="account-mini-row">
                                <span>Total Expense</span>
                                <strong>Rs {{ number_format($totalExpense, 2) }}</strong>
                            </div>

                            <div class="account-mini-row">
                                <span>Remaining</span>
                                <strong>Rs {{ number_format($remainingAmount, 2) }}</strong>
                            </div>

                            <div class="account-mini-row">
                                <span>Transactions</span>
                                <strong>{{ $account->transactions->count() }}</strong>
                            </div>

                            <div class="account-mini-row">
                                <span>Created</span>
                                <strong>{{ optional($account->created_at)->format('d M Y') }}</strong>
                            </div>

                        </div>

                    @else
                        <div class="empty-state">
                            <strong>No account</strong>
                            No account has been created for this trip.
                        </div>
                    @endif
                </div>
            </div>

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
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY', 'AIzaSyDQqP59sFi_cXyk8Afq_AY4Dkg4DCf-xj0') }}&libraries=places"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.js"></script>

<script>
    Pusher.logToConsole = true;

    let map;
    let directionsRenderer;
    let driverMarker = null;

    function initMap() {

        const pickup = {
            lat: parseFloat("{{ $trip->pickup_lat ?? 24.8607 }}"),
            lng: parseFloat("{{ $trip->pickup_lng ?? 67.0011 }}")
        };

        const delivery = {
            lat: parseFloat("{{ $trip->delivery_lat ?? 24.8607 }}"),
            lng: parseFloat("{{ $trip->delivery_lng ?? 67.0011 }}")
        };

        map = new google.maps.Map(document.getElementById("map"), {
            center: pickup,
            zoom: 7
        });

        const directionsService = new google.maps.DirectionsService();

        directionsRenderer = new google.maps.DirectionsRenderer({
            suppressMarkers: true,
            polylineOptions: {
                strokeColor: "#4f46e5",
                strokeWeight: 5
            }
        });

        directionsRenderer.setMap(map);

        directionsService.route({
            origin: pickup,
            destination: delivery,
            travelMode: "DRIVING"
        }, function (response, status) {
            if (status === "OK") {
                directionsRenderer.setDirections(response);
            }
        });

        const pickupMarker = new google.maps.Marker({
            position: pickup,
            map: map,
            label: "P",
            title: "Pickup Location"
        });

        const pickupInfo = new google.maps.InfoWindow({
            content: `
                <div style="min-width:200px">
                    <h4 style="margin-bottom:5px;">Pickup Location</h4>
                    <p>{{ $trip->pickup_location }}</p>
                </div>
            `
        });

        pickupMarker.addListener("click", () => {
            pickupInfo.open(map, pickupMarker);
        });

        const deliveryMarker = new google.maps.Marker({
            position: delivery,
            map: map,
            label: "D",
            title: "Delivery Location"
        });

        const deliveryInfo = new google.maps.InfoWindow({
            content: `
                <div style="min-width:200px">
                    <h4 style="margin-bottom:5px;">Delivery Location</h4>
                    <p>{{ $trip->delivery_location }}</p>
                </div>
            `
        });

        deliveryMarker.addListener("click", () => {
            deliveryInfo.open(map, deliveryMarker);
        });

        @if($trip->driver && $trip->driver->location)

            const driverPosition = {
                lat: parseFloat("{{ $trip->driver->location->latitude }}"),
                lng: parseFloat("{{ $trip->driver->location->longitude }}")
            };

            driverMarker = new google.maps.Marker({
                position: driverPosition,
                map: map,
                title: "Driver Live Location",
                icon: {
                    url: "http://maps.google.com/mapfiles/ms/icons/orange-dot.png"
                }
            });

            const driverInfo = new google.maps.InfoWindow({
                content: `
                    <div style="min-width:200px">
                        <h4>Driver Live Location</h4>
                        <p><strong>Driver:</strong> {{ $trip->driver->full_name }}</p>
                        <p><strong>Speed:</strong> {{ $trip->driver->location->speed ?? 0 }} km/h</p>
                        <p><strong>Updated:</strong> {{ $trip->driver->location->updated_at }}</p>
                    </div>
                `
            });

            driverMarker.addListener("click", () => {
                driverInfo.open(map, driverMarker);
            });

        @endif
    }

    window.onload = function () {

        initMap();

        @if($trip->driver)
            const echo = new Echo({
                broadcaster: 'pusher',
                key: "{{ env('PUSHER_APP_KEY') }}",
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
                forceTLS: true,
                authEndpoint: '/broadcasting/auth',
                withCredentials: true,
            });

            echo.private('admin.' + {{ $trip->driver->admin_id ?? 0 }})
                .listen('.driver.location.updated', (e) => {

                    const lat = e.location?.latitude ?? e.latitude;
                    const lng = e.location?.longitude ?? e.longitude;

                    if (!lat || !lng) return;

                    const newPosition = {
                        lat: parseFloat(lat),
                        lng: parseFloat(lng)
                    };

                    if (driverMarker) {
                        driverMarker.setPosition(newPosition);
                        map.panTo(newPosition);
                    }
                });
        @endif
    };
</script>

@endsection