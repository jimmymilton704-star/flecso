@extends('layouts.app')

@section('title', 'Trips')
@section('body-class', 'page-dashboard')

@section('content')

    <section class="page">

        <style>
            .trip-toolbar-card {
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 22px;
                padding: 16px;
                box-shadow: 0 12px 32px rgba(15, 23, 42, .05);
                margin-bottom: 18px;
            }

            .trip-card-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 18px;
            }

            .trip-card {
                position: relative;
                overflow: hidden;
                background: #ffffff;
                border: 1px solid #e5e7eb;
                border-radius: 24px;
                box-shadow: 0 16px 40px rgba(15, 23, 42, .06);
                transition: all .22s ease;
            }

            .trip-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 24px 60px rgba(15, 23, 42, .10);
                border-color: #cbd5e1;
            }

            .trip-card__top {
                padding: 18px 18px 14px;
                background:
                    radial-gradient(circle at top right, rgba(59, 130, 246, .12), transparent 32%),
                    linear-gradient(180deg, #f8fafc, #ffffff);
                border-bottom: 1px solid #eef2f7;
            }

            .trip-card__head {
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 14px;
            }

            .trip-card__id {
                font-size: 17px;
                font-weight: 900;
                color: #0f172a;
                margin-bottom: 4px;
            }

            .trip-card__type {
                font-size: 12px;
                color: #64748b;
                font-weight: 700;
                text-transform: capitalize;
            }

            .trip-card__route {
                margin-top: 16px;
                display: grid;
                grid-template-columns: auto 1fr;
                gap: 12px;
                align-items: stretch;
            }

            .route-pins {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding-top: 2px;
            }

            .route-pin {
                width: 28px;
                height: 28px;
                border-radius: 999px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 11px;
                font-weight: 900;
                color: #ffffff;
            }

            .route-pin.from {
                background: #2563eb;
            }

            .route-pin.to {
                background: #16a34a;
            }

            .route-line {
                width: 2px;
                flex: 1;
                min-height: 28px;
                background: linear-gradient(180deg, #2563eb, #16a34a);
                opacity: .35;
                margin: 4px 0;
                border-radius: 999px;
            }

            .route-text {
                display: flex;
                flex-direction: column;
                gap: 11px;
                min-width: 0;
            }

            .route-place span {
                display: block;
                font-size: 11px;
                color: #94a3b8;
                text-transform: uppercase;
                font-weight: 800;
                letter-spacing: .04em;
                margin-bottom: 3px;
            }

            .route-place strong {
                display: block;
                color: #0f172a;
                font-size: 13px;
                line-height: 1.45;
            }

            .trip-card__body {
                padding: 16px 18px 18px;
            }

            .trip-info-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
                margin-bottom: 14px;
            }

            .trip-info {
                background: #f8fafc;
                border: 1px solid #eef2f7;
                border-radius: 16px;
                padding: 12px;
                min-width: 0;
            }

            .trip-info span {
                display: block;
                font-size: 11px;
                color: #64748b;
                font-weight: 800;
                text-transform: uppercase;
                letter-spacing: .03em;
                margin-bottom: 6px;
            }

            .trip-info strong {
                display: block;
                color: #0f172a;
                font-size: 13px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .account-panel {
                border: 1px solid #e5e7eb;
                border-radius: 18px;
                background: #ffffff;
                padding: 14px;
                margin-top: 12px;
            }

            .account-panel__head {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 12px;
                margin-bottom: 12px;
            }

            .account-panel__title {
                font-size: 13px;
                font-weight: 900;
                color: #0f172a;
            }

            .account-panel__count {
                font-size: 11px;
                color: #64748b;
                font-weight: 800;
                background: #f1f5f9;
                padding: 5px 8px;
                border-radius: 999px;
            }

            .account-money-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
            }

            .account-money {
                border-radius: 14px;
                padding: 10px;
                background: #f8fafc;
                border: 1px solid #eef2f7;
            }

            .account-money.opening {
                background: #eff6ff;
                border-color: #bfdbfe;
            }

            .account-money.expense {
                background: #fff7ed;
                border-color: #fed7aa;
            }

            .account-money.remaining {
                background: #ecfdf5;
                border-color: #bbf7d0;
            }

            .account-money.danger {
                background: #fef2f2;
                border-color: #fecaca;
            }

            .account-money span {
                display: block;
                font-size: 10px;
                color: #64748b;
                font-weight: 900;
                text-transform: uppercase;
                margin-bottom: 5px;
            }

            .account-money strong {
                display: block;
                color: #0f172a;
                font-size: 13px;
                font-weight: 900;
                white-space: nowrap;
            }

            .account-progress {
                width: 100%;
                height: 8px;
                background: #e5e7eb;
                border-radius: 999px;
                overflow: hidden;
                margin-top: 12px;
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
                margin-top: 7px;
                font-size: 11px;
                color: #64748b;
                font-weight: 800;
            }

            .no-account {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 18px;
                border-radius: 16px;
                background: #fff7ed;
                color: #c2410c;
                font-size: 13px;
                font-weight: 900;
                border: 1px dashed #fed7aa;
            }

            .trip-card__actions {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                margin-top: 16px;
                padding-top: 14px;
                border-top: 1px solid #eef2f7;
            }

            .action-left,
            .action-right {
                display: flex;
                align-items: center;
                gap: 7px;
                flex-wrap: wrap;
            }

            .mini-btn {
                width: 36px;
                height: 36px;
                border-radius: 12px;
                border: 1px solid #e5e7eb;
                background: #ffffff;
                color: #334155;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all .2s ease;
                text-decoration: none;
            }

            .mini-btn:hover {
                transform: translateY(-1px);
                box-shadow: 0 8px 20px rgba(15, 23, 42, .10);
                border-color: #cbd5e1;
                color: #0f172a;
            }

            .mini-btn--primary {
                background: #eff6ff;
                color: #2563eb;
                border-color: #bfdbfe;
            }

            .mini-btn--success {
                background: #ecfdf5;
                color: #16a34a;
                border-color: #bbf7d0;
            }

            .mini-btn--danger {
                background: #fef2f2;
                color: #dc2626;
                border-color: #fecaca;
            }

            .mini-btn--copy {
                background: #f8fafc;
                color: #475569;
            }

            .copyTrackingBtn.copied {
                width: auto;
                padding: 0 12px;
                font-size: 12px;
                font-weight: 800;
                color: #16a34a;
                background: #ecfdf5;
                border-color: #bbf7d0;
            }

            .empty-card {
                grid-column: 1 / -1;
                padding: 42px 16px;
                text-align: center;
                background: #ffffff;
                border: 1px dashed #cbd5e1;
                border-radius: 22px;
                color: #64748b;
            }

            .empty-card strong {
                display: block;
                color: #0f172a;
                font-size: 18px;
                margin-bottom: 6px;
            }

            .custom-modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, .60);
                backdrop-filter: blur(4px);
                display: none;
                align-items: center;
                justify-content: center;
                padding: 24px;
                z-index: 99999;
            }

            .custom-modal-overlay.active {
                display: flex;
            }

            .custom-modal {
                width: 100%;
                max-width: 980px;
                max-height: 90vh;
                background: #ffffff;
                border-radius: 22px;
                overflow: hidden;
                box-shadow: 0 30px 80px rgba(15, 23, 42, .30);
                border: 1px solid #e5e7eb;
            }

            .custom-modal__head {
                padding: 20px 24px;
                border-bottom: 1px solid #e5e7eb;
                display: flex;
                align-items: flex-start;
                justify-content: space-between;
                gap: 16px;
                background: linear-gradient(180deg, #f8fafc, #ffffff);
            }

            .custom-modal__title {
                margin: 0;
                font-size: 20px;
                font-weight: 900;
                color: #0f172a;
            }

            .custom-modal__sub {
                margin-top: 4px;
                font-size: 13px;
                color: #64748b;
            }

            .custom-modal__close {
                width: 36px;
                height: 36px;
                border-radius: 12px;
                border: 1px solid #e5e7eb;
                background: #ffffff;
                color: #334155;
                cursor: pointer;
                font-size: 22px;
                line-height: 1;
            }

            .custom-modal__body {
                padding: 24px;
                max-height: calc(90vh - 82px);
                overflow-y: auto;
            }

            .modal-stats-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 14px;
                margin-bottom: 20px;
            }

            .modal-stat {
                border-radius: 16px;
                padding: 16px;
                border: 1px solid #e5e7eb;
                background: #f8fafc;
            }

            .modal-stat.green {
                background: #ecfdf5;
                border-color: #bbf7d0;
            }

            .modal-stat.orange {
                background: #fff7ed;
                border-color: #fed7aa;
            }

            .modal-stat.blue {
                background: #eff6ff;
                border-color: #bfdbfe;
            }

            .modal-stat__label {
                font-size: 12px;
                color: #64748b;
                margin-bottom: 8px;
                font-weight: 800;
            }

            .modal-stat__value {
                font-size: 22px;
                font-weight: 900;
                color: #0f172a;
            }

            .detail-grid-modal {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
                margin-top: 14px;
            }

            .detail-item {
                border: 1px solid #e5e7eb;
                border-radius: 14px;
                padding: 13px 14px;
                background: #ffffff;
            }

            .detail-item span {
                display: block;
                font-size: 12px;
                color: #64748b;
                margin-bottom: 4px;
            }

            .detail-item strong {
                color: #0f172a;
                font-size: 14px;
            }

            .modal-table-wrap {
                overflow-x: auto;
                border: 1px solid #e5e7eb;
                border-radius: 16px;
            }

            table.modal-table {
                width: 100%;
                min-width: 850px;
                border-collapse: collapse;
            }

            table.modal-table th {
                background: #f8fafc;
                color: #475569;
                font-size: 12px;
                text-align: left;
                padding: 12px;
                border-bottom: 1px solid #e5e7eb;
                white-space: nowrap;
            }

            table.modal-table td {
                padding: 12px;
                border-bottom: 1px solid #eef2f7;
                font-size: 13px;
                color: #334155;
                vertical-align: top;
            }

            table.modal-table tr:last-child td {
                border-bottom: 0;
            }

            .tx-type {
                display: inline-flex;
                align-items: center;
                padding: 5px 9px;
                border-radius: 999px;
                background: #eff6ff;
                color: #2563eb;
                font-size: 12px;
                font-weight: 800;
                white-space: nowrap;
            }

            .empty-modal {
                text-align: center;
                padding: 35px 15px;
                color: #64748b;
                background: #f8fafc;
                border-radius: 16px;
                border: 1px dashed #cbd5e1;
            }

            @media (max-width: 1200px) {
                .trip-card-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 768px) {

                .trip-info-grid,
                .account-money-grid,
                .modal-stats-grid,
                .detail-grid-modal {
                    grid-template-columns: 1fr;
                }

                .trip-card__actions {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .custom-modal {
                    max-height: 94vh;
                }

                .custom-modal__body {
                    max-height: calc(94vh - 82px);
                }
            }

            .export-csv-btn {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                border: 1px solid #fed7aa;
                background: #fff7ed;
                color: #c2410c;
                font-weight: 800;
            }

            .export-csv-btn:hover {
                background: #ffedd5;
                border-color: #fdba74;
                color: #9a3412;
            }

            .modal-export-btn {
                display: inline-flex;
                align-items: center;
                gap: 7px;
                border: none;
                background: linear-gradient(135deg, #f59e0b, #fb923c);
                color: #ffffff;
                padding: 9px 12px;
                border-radius: 11px;
                font-size: 12px;
                font-weight: 800;
                cursor: pointer;
                white-space: nowrap;
            }

            .modal-export-btn:hover {
                opacity: .92;
            }

            .custom-modal__head-actions {
                display: flex;
                align-items: center;
                gap: 10px;
            }
        </style>

        <div class="page-head">
            <div>
                <div class="breadcrumb">Operations <span>/ Trips</span></div>
                <h1>Trips</h1>
                <div class="page-head__sub">Manage all logistics trips, status, accounts, and transactions.</div>
            </div>

            <div class="page-head__actions">
                <button type="button" class="btn btn--ghost export-csv-btn" onclick="exportTripsCSV()">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <path d="M7 10l5 5 5-5" />
                        <path d="M12 15V3" />
                    </svg>
                    Export CSV
                </button>

                <a href="{{ route('trips.create') }}" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Add New Trip
                </a>
            </div>
        </div>

        {{-- STATS --}}
        <div class="stats-grid">

            <div class="stat">
                <div class="stat__icon stat__icon--green">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 13h18M5 13l2-6h10l2 6" />
                        <circle cx="7" cy="17" r="2" />
                        <circle cx="17" cy="17" r="2" />
                    </svg>
                </div>
                <div class="stat__label">Active</div>
                <div class="stat__value">{{ $trips->where('trip_status', 'active')->count() }}</div>
            </div>

            <div class="stat">
                <div class="stat__icon stat__icon--blue">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 13h18M5 13l2-6h10l2 6" />
                        <circle cx="7" cy="17" r="2" />
                        <circle cx="17" cy="17" r="2" />
                    </svg>
                </div>
                <div class="stat__label">Pending</div>
                <div class="stat__value">{{ $trips->where('trip_status', 'pending')->count() }}</div>
            </div>

            <div class="stat">
                <div class="stat__icon stat__icon--dark">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 13h18M5 13l2-6h10l2 6" />
                        <circle cx="7" cy="17" r="2" />
                        <circle cx="17" cy="17" r="2" />
                    </svg>
                </div>
                <div class="stat__label">Completed</div>
                <div class="stat__value">{{ $trips->where('trip_status', 'completed')->count() }}</div>
            </div>

            <div class="stat">
                <div class="stat__icon stat__icon--orange">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 13h18M5 13l2-6h10l2 6" />
                        <circle cx="7" cy="17" r="2" />
                        <circle cx="17" cy="17" r="2" />
                    </svg>
                </div>
                <div class="stat__label">Cancelled</div>
                <div class="stat__value">{{ $trips->where('trip_status', 'cancelled')->count() }}</div>
            </div>

        </div>

        {{-- TOOLBAR --}}
        <div class="trip-toolbar-card">
            <div class="toolbar" style="flex:1">
                <div class="search">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="11" cy="11" r="7" />
                        <path d="m20 20-3-3" />
                    </svg>
                    <input type="text" id="tripSearch"
                        placeholder="Search by trip, location, truck, container or driver…" />
                </div>

                <div class="filters">
                    <button class="active" data-filter="all">All</button>
                    <button data-filter="active">Active</button>
                    <button data-filter="in_transit">In Transit</button>
                    <button data-filter="pending">Pending</button>
                    <button data-filter="completed">Completed</button>
                    <button data-filter="cancelled">Cancelled</button>
                </div>
            </div>
        </div>

        {{-- CARDS --}}
        <div class="trip-card-grid">

            @forelse($trips as $trip)

                @php
                    $account = $trip->account;

                    $openingAmount = $account ? (float) $account->opening_amount : 0;
                    $totalExpense = $account ? (float) $account->total_expense : 0;
                    $remainingAmount = $account ? (float) $account->remaining_amount : 0;

                    $usedPercent = $openingAmount > 0 ? min(100, round(($totalExpense / $openingAmount) * 100)) : 0;

                    $barClass = $usedPercent >= 85 ? 'danger' : ($usedPercent >= 60 ? 'warning' : '');

                    $rowSearch = strtolower(
                        ($trip->trip_id ?? '') .
                            ' ' .
                            ($trip->trip_type ?? '') .
                            ' ' .
                            ($trip->pickup_location ?? '') .
                            ' ' .
                            ($trip->delivery_location ?? '') .
                            ' ' .
                            ($trip->driver->full_name ?? '') .
                            ' ' .
                            ($trip->truck->truck_number ?? '') .
                            ' ' .
                            ($trip->container->container_license_number ?? ''),
                    );

                    $trackingUrl = route('trip.track', [
                        'token' => urlencode(encrypt($trip->id)),
                    ]);
                @endphp

                <div class="trip-card" data-status="{{ $trip->trip_status }}" data-search="{{ $rowSearch }}">

                    <div class="trip-card__top">
                        <div class="trip-card__head">
                            <div>
                                <div class="trip-card__id">{{ $trip->trip_id }}</div>
                                <div class="trip-card__type">
                                    {{ ucfirst(str_replace('_', ' ', $trip->trip_type ?? 'N/A')) }}</div>
                            </div>

                            <span
                                class="badge
                            @if ($trip->trip_status == 'active') badge--success
                            @elseif($trip->trip_status == 'pending') badge--warning
                            @elseif($trip->trip_status == 'completed') badge--neutral
                            @elseif($trip->trip_status == 'in_transit') badge--success
                            @else badge--danger @endif">
                                {{ ucfirst(str_replace('_', ' ', $trip->trip_status)) }}
                            </span>
                        </div>

                        <div class="trip-card__route">
                            <div class="route-pins">
                                <div class="route-pin from">A</div>
                                <div class="route-line"></div>
                                <div class="route-pin to">B</div>
                            </div>

                            <div class="route-text">
                                <div class="route-place">
                                    <span>Pickup</span>
                                    <strong>{{ $trip->pickup_location ?? 'N/A' }}</strong>
                                </div>

                                <div class="route-place">
                                    <span>Delivery</span>
                                    <strong>{{ $trip->delivery_location ?? 'N/A' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="trip-card__body">

                        <div class="trip-info-grid">
                            <div class="trip-info">
                                <span>Driver</span>
                                <strong>{{ $trip->driver->full_name ?? 'N/A' }}</strong>
                            </div>

                            <div class="trip-info">
                                <span>Truck</span>
                                <strong>{{ $trip->truck->truck_number ?? 'N/A' }}</strong>
                            </div>

                            <div class="trip-info">
                                <span>Container</span>
                                <strong>{{ $trip->container->container_license_number ?? 'N/A' }}</strong>
                            </div>

                            <div class="trip-info">
                                <span>Amount</span>
                                <strong>${{ number_format($trip->payment_amount ?? 0, 2) }}</strong>
                            </div>

                            <div class="trip-info">
                                <span>Distance</span>
                                <strong>{{ number_format($trip->distance_km ?? 0, 2) }} km</strong>
                            </div>

                            <div class="trip-info">
                                <span>Schedule</span>
                                <strong>{{ $trip->schedule_datetime ?? 'N/A' }}</strong>
                            </div>
                        </div>

                        @if ($account)
                            <div class="account-panel">
                                <div class="account-panel__head">
                                    <div class="account-panel__title">Account Overview</div>
                                    <div class="account-panel__count">
                                        {{ $account->transactions->count() }} Transactions
                                    </div>
                                </div>

                                <div class="account-money-grid">
                                    <div class="account-money opening">
                                        <span>Opening</span>
                                        <strong>${{ number_format($openingAmount, 2) }}</strong>
                                    </div>

                                    <div class="account-money expense">
                                        <span>Expense</span>
                                        <strong>${{ number_format($totalExpense, 2) }}</strong>
                                    </div>

                                    <div class="account-money remaining {{ $remainingAmount <= 0 ? 'danger' : '' }}">
                                        <span>Remaining</span>
                                        <strong>${{ number_format($remainingAmount, 2) }}</strong>
                                    </div>
                                </div>

                                <div class="account-progress">
                                    <div class="account-progress__bar {{ $barClass }}"
                                        style="width: {{ $usedPercent }}%;"></div>
                                </div>

                                <div class="account-progress-meta">
                                    <span>{{ $usedPercent }}% used</span>
                                    <span>{{ ucfirst($account->status ?? 'active') }}</span>
                                </div>
                            </div>
                        @else
                            <div class="no-account">
                                No Account Found
                            </div>
                        @endif

                        <div class="trip-card__actions">
                            <div class="action-left">
                                <a href="{{ route('trips.show', $trip->id) }}" class="mini-btn" title="View Trip">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </a>

                                <a href="{{ route('trips.edit', $trip->id) }}" class="mini-btn" title="Edit Trip">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14" />
                                        <path d="M18.5 2.5l3 3L12 15l-4 1 1-4z" />
                                    </svg>
                                </a>

                                <button type="button" class="mini-btn mini-btn--primary open-modal"
                                    data-modal="accountModal{{ $trip->id }}" title="Account Detail">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="16" rx="2" />
                                        <path d="M7 8h10M7 12h10M7 16h6" />
                                    </svg>
                                </button>

                                <button type="button" class="mini-btn mini-btn--success open-modal"
                                    data-modal="transactionModal{{ $trip->id }}" title="Transactions">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M7 7h11l-3-3" />
                                        <path d="M17 17H6l3 3" />
                                        <path d="M18 7a7 7 0 0 1-1 10" />
                                        <path d="M6 17a7 7 0 0 1 1-10" />
                                    </svg>
                                </button>

                                <button class="mini-btn mini-btn--copy copyTrackingBtn" data-link="{{ $trackingUrl }}"
                                    type="button" title="Copy Tracking Link">
                                    <svg viewBox="0 0 24 24" width="15" height="15" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <rect x="9" y="9" width="13" height="13" rx="2" />
                                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                    </svg>
                                </button>
                            </div>

                            <div class="action-right">
                                <form action="{{ route('trips.destroy', $trip->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this trip?');">
                                    @csrf


                                    <button class="mini-btn mini-btn--danger" title="Delete Trip" type="submit">
                                        <svg viewBox="0 0 24 24" width="15" height="15" fill="none"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            @empty
                <div class="empty-card">
                    <strong>No trips found</strong>
                    Start by creating your first trip.
                </div>
            @endforelse

        </div>

        {{-- MODALS --}}
        @foreach ($trips as $trip)
            @php
                $account = $trip->account;

                $openingAmount = $account ? (float) $account->opening_amount : 0;
                $totalExpense = $account ? (float) $account->total_expense : 0;
                $remainingAmount = $account ? (float) $account->remaining_amount : 0;

                $usedPercent = $openingAmount > 0 ? min(100, round(($totalExpense / $openingAmount) * 100)) : 0;

                $barClass = $usedPercent >= 85 ? 'danger' : ($usedPercent >= 60 ? 'warning' : '');
            @endphp

            {{-- ACCOUNT DETAIL MODAL --}}
            <div class="custom-modal-overlay" id="accountModal{{ $trip->id }}">
                <div class="custom-modal">

                    <div class="custom-modal__head">
                        <div>
                            <h2 class="custom-modal__title">Trip Account Detail</h2>
                            <div class="custom-modal__sub">
                                Trip: {{ $trip->trip_id }} |
                                Driver: {{ $trip->driver->full_name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="custom-modal__head-actions">
                            <button type="button" class="modal-export-btn"
                                onclick="exportTripTransactionsCSV({{ $trip->id }}, '{{ addslashes($trip->trip_id) }}')">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <path d="M7 10l5 5 5-5" />
                                    <path d="M12 15V3" />
                                </svg>
                                Export
                            </button>

                            <button type="button" class="custom-modal__close close-modal">&times;</button>
                        </div>

                        <button type="button" class="custom-modal__close close-modal">&times;</button>
                    </div>

                    <div class="custom-modal__body">
                        @if ($account)
                            <div class="modal-stats-grid">
                                <div class="modal-stat blue">
                                    <div class="modal-stat__label">Opening Amount</div>
                                    <div class="modal-stat__value">${{ number_format($openingAmount, 2) }}</div>
                                </div>

                                <div class="modal-stat orange">
                                    <div class="modal-stat__label">Total Expense</div>
                                    <div class="modal-stat__value">${{ number_format($totalExpense, 2) }}</div>
                                </div>

                                <div class="modal-stat green">
                                    <div class="modal-stat__label">Remaining Amount</div>
                                    <div class="modal-stat__value">${{ number_format($remainingAmount, 2) }}</div>
                                </div>
                            </div>

                            <div class="account-progress" style="height: 10px; margin-bottom: 18px;">
                                <div class="account-progress__bar {{ $barClass }}"
                                    style="width: {{ $usedPercent }}%;"></div>
                            </div>

                            <div class="detail-grid-modal">
                                <div class="detail-item">
                                    <span>Account ID</span>
                                    <strong>#{{ $account->id }}</strong>
                                </div>

                                <div class="detail-item">
                                    <span>Account Status</span>
                                    <strong>{{ ucfirst($account->status ?? 'active') }}</strong>
                                </div>

                                <div class="detail-item">
                                    <span>Trip ID</span>
                                    <strong>{{ $trip->trip_id }}</strong>
                                </div>

                                <div class="detail-item">
                                    <span>Driver</span>
                                    <strong>{{ $trip->driver->full_name ?? 'N/A' }}</strong>
                                </div>

                                <div class="detail-item">
                                    <span>Truck</span>
                                    <strong>{{ $trip->truck->truck_number ?? 'N/A' }}</strong>
                                </div>

                                <div class="detail-item">
                                    <span>Container</span>
                                    <strong>{{ $trip->container->container_license_number ?? 'N/A' }}</strong>
                                </div>

                                <div class="detail-item">
                                    <span>Pickup Location</span>
                                    <strong>{{ $trip->pickup_location ?? 'N/A' }}</strong>
                                </div>

                                <div class="detail-item">
                                    <span>Delivery Location</span>
                                    <strong>{{ $trip->delivery_location ?? 'N/A' }}</strong>
                                </div>

                                <div class="detail-item">
                                    <span>Total Transactions</span>
                                    <strong>{{ $account->transactions->count() }}</strong>
                                </div>

                                <div class="detail-item">
                                    <span>Account Created</span>
                                    <strong>{{ optional($account->created_at)->format('d M Y, h:i A') }}</strong>
                                </div>
                            </div>
                        @else
                            <div class="empty-modal">
                                No trip account found for this trip.
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            {{-- TRANSACTIONS MODAL --}}
            <div class="custom-modal-overlay" id="transactionModal{{ $trip->id }}">
                <div class="custom-modal">

                    <div class="custom-modal__head">
                        <div>
                            <h2 class="custom-modal__title">Account Transactions</h2>
                            <div class="custom-modal__sub">
                                Trip: {{ $trip->trip_id }} |
                                Driver: {{ $trip->driver->full_name ?? 'N/A' }}
                            </div>
                        </div>

                        <div class="custom-modal__head-actions">
                            <button type="button" class="modal-export-btn"
                                onclick='exportTripTransactionsCSV({{ $trip->id }}, @json($trip->trip_id))'>
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                    <path d="M7 10l5 5 5-5" />
                                    <path d="M12 15V3" />
                                </svg>
                                Export
                            </button>

                            <button type="button" class="custom-modal__close close-modal">&times;</button>
                        </div>
                    </div>

                    <div class="custom-modal__body">
                        @if ($account && $account->transactions->count() > 0)
                            <div class="modal-table-wrap">
                                <table class="modal-table">
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
                                        @foreach ($account->transactions as $transaction)
                                            <tr>
                                                <td>
                                                    @if ($transaction->expense_date)
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
                                                    <strong>{{ $transaction->source_name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small>
                                                        {{ $transaction->source_type ?? 'N/A' }}
                                                        @if ($transaction->source_id)
                                                            #{{ $transaction->source_id }}
                                                        @endif
                                                    </small>
                                                </td>

                                                <td>
                                                    <strong>${{ number_format($transaction->amount ?? 0, 2) }}</strong>
                                                </td>

                                                <td>
                                                    ${{ number_format($transaction->balance_before ?? 0, 2) }}
                                                </td>

                                                <td>
                                                    ${{ number_format($transaction->balance_after ?? 0, 2) }}
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
                            <div class="empty-modal">
                                No transactions found for this trip account.
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        @endforeach

        <div class="pagination" style="margin-top:18px">
            <div class="meta">
                Showing {{ $trips->firstItem() ?? 0 }}–{{ $trips->lastItem() ?? 0 }} of {{ $trips->total() }} trips
            </div>
            <div class="pager">
                {{ $trips->links() }}
            </div>
        </div>

    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.filters button');
            const cards = document.querySelectorAll('.trip-card[data-status]');
            const searchInput = document.getElementById('tripSearch');

            let activeFilter = 'all';

            function applyFilters() {
                const searchValue = (searchInput?.value || '').toLowerCase().trim();

                cards.forEach(card => {
                    const status = card.dataset.status;
                    const rowSearch = card.dataset.search || '';

                    const matchStatus = activeFilter === 'all' || status === activeFilter;
                    const matchSearch = !searchValue || rowSearch.includes(searchValue);

                    card.style.display = matchStatus && matchSearch ? '' : 'none';
                });
            }

            buttons.forEach(btn => {
                btn.addEventListener('click', function() {
                    buttons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    activeFilter = this.dataset.filter;
                    applyFilters();
                });
            });

            if (searchInput) {
                searchInput.addEventListener('keyup', applyFilters);
            }
        });
    </script>

    <script>
        document.querySelectorAll('.copyTrackingBtn').forEach(btn => {
            btn.addEventListener('click', async function() {
                const link = this.dataset.link;
                const oldHtml = this.innerHTML;

                try {
                    await navigator.clipboard.writeText(link);

                    this.classList.add('copied');
                    this.innerHTML = 'Copied';

                    setTimeout(() => {
                        this.classList.remove('copied');
                        this.innerHTML = oldHtml;
                    }, 1500);

                } catch (error) {
                    alert('Unable to copy tracking link.');
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openButtons = document.querySelectorAll('.open-modal');
            const closeButtons = document.querySelectorAll('.close-modal');
            const modals = document.querySelectorAll('.custom-modal-overlay');

            openButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const modalId = this.dataset.modal;
                    const modal = document.getElementById(modalId);

                    if (modal) {
                        modal.classList.add('active');
                        document.body.style.overflow = 'hidden';
                    }
                });
            });

            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const modal = this.closest('.custom-modal-overlay');

                    if (modal) {
                        modal.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            });

            modals.forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === this) {
                        this.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                });
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    modals.forEach(modal => modal.classList.remove('active'));
                    document.body.style.overflow = '';
                }
            });
        });
    </script>
    @php
        $tripsExportData = $trips
            ->map(function ($trip) {
                return [
                    'Trip ID' => $trip->trip_id,
                    'Trip Type' => $trip->trip_type,
                    'Status' => $trip->trip_status,
                    'Pickup Location' => $trip->pickup_location,
                    'Delivery Location' => $trip->delivery_location,
                    'Driver' => optional($trip->driver)->full_name,
                    'Truck' => optional($trip->truck)->truck_number,
                    'Container' => optional($trip->container)->container_license_number,
                    'Payment Amount' => $trip->payment_amount,
                    'Distance KM' => $trip->distance_km,
                    'ETA Minutes' => $trip->eta_mins,
                    'Schedule' => $trip->schedule_datetime,
                    'Opening Amount' => optional($trip->account)->opening_amount,
                    'Total Expense' => optional($trip->account)->total_expense,
                    'Remaining Amount' => optional($trip->account)->remaining_amount,
                    'Account Status' => optional($trip->account)->status,
                    'Total Transactions' => $trip->account ? $trip->account->transactions->count() : 0,
                    'Created At' => optional($trip->created_at)->format('d M Y, h:i A'),
                ];
            })
            ->values();

        $tripTransactionsExportData = $trips->mapWithKeys(function ($trip) {
            return [
                $trip->id => $trip->account
                    ? $trip->account->transactions
                        ->map(function ($transaction) use ($trip) {
                            return [
                                'Trip ID' => $trip->trip_id,
                                'Driver' => optional($trip->driver)->full_name,
                                'Date' => $transaction->expense_date
                                    ? \Carbon\Carbon::parse($transaction->expense_date)->format('d M Y')
                                    : optional($transaction->created_at)->format('d M Y'),
                                'Type' => $transaction->type,
                                'Title' => $transaction->title,
                                'Source Name' => $transaction->source_name,
                                'Source Type' => $transaction->source_type,
                                'Source ID' => $transaction->source_id,
                                'Amount' => $transaction->amount,
                                'Balance Before' => $transaction->balance_before,
                                'Balance After' => $transaction->balance_after,
                                'Description' => $transaction->description,
                            ];
                        })
                        ->values()
                    : collect(),
            ];
        });
    @endphp

    <script>
        window.tripsExportData = @json($tripsExportData);
        window.tripTransactionsExportData = @json($tripTransactionsExportData);
    </script>
    <script>
        function exportTripsCSV() {
            const rows = window.tripsExportData || [];

            if (!rows.length) {
                alert('No trips available to export.');
                return;
            }

            downloadCSV(rows, 'trips_export.csv');
        }

        function exportTripTransactionsCSV(tripId, tripLabel) {
            const rows = window.tripTransactionsExportData?.[tripId] || [];

            if (!rows.length) {
                alert('No transactions available for this trip.');
                return;
            }

            const safeTripLabel = String(tripLabel || 'trip')
                .replace(/[^a-z0-9_-]/gi, '_')
                .toLowerCase();

            downloadCSV(rows, `transactions_${safeTripLabel}.csv`);
        }

        function downloadCSV(rows, filename) {
            if (!rows || !rows.length) return;

            const headers = Object.keys(rows[0]);

            const csvRows = [
                headers.map(csvEscape).join(','),
                ...rows.map(row => {
                    return headers.map(header => {
                        return csvEscape(row[header]);
                    }).join(',');
                })
            ];

            const csvContent = csvRows.join('\n');

            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });

            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');

            link.href = url;
            link.download = filename;
            link.style.display = 'none';

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            URL.revokeObjectURL(url);
        }

        function csvEscape(value) {
            if (value === null || value === undefined) {
                return '""';
            }

            const stringValue = String(value).replace(/"/g, '""');

            return `"${stringValue}"`;
        }
    </script>

@endsection
