@extends('layouts.app')

@section('title', 'Trips')
@section('body-class', 'page-dashboard')

@section('content')

    <section class="page">
        <div class="page-head">
            <div>
                <div class="breadcrumb">Operations <span>/ Trips</span></div>
                <h1>Trips</h1>
                <div class="page-head__sub">Manage all logistics trips, status, and assignments.</div>
            </div>

            <div class="page-head__actions">
                <a href="{{ route('trips.create') }}" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
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
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
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
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
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
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
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
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 13h18M5 13l2-6h10l2 6" />
                        <circle cx="7" cy="17" r="2" />
                        <circle cx="17" cy="17" r="2" />
                    </svg>
                </div>
                <div class="stat__label">Cancelled</div>
                <div class="stat__value">{{ $trips->where('trip_status', 'cancelled')->count() }}</div>
            </div>

        </div>

        {{-- TABLE --}}
        <div class="card">

            <div class="card__head">
                <div class="toolbar" style="flex:1">

                    <div class="search">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3-3" />
                        </svg>
                        <input type="text" placeholder="Search by trip, location or driver…" />
                    </div>

                    <div class="filters">
                        <button class="active" data-filter="all">All</button>
                        <button data-filter="in_transit">In Transit</button>
                        <button data-filter="completed">Completed</button>
                        <button data-filter="cancelled">Cancelled</button>
                    </div>

                </div>
            </div>

            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Trip</th>
                            <th>Route</th>
                            <th>Driver</th>
                            <th>Vehicle</th>
                            <th>Container</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($trips as $trip)
                            <tr data-status="{{ $trip->trip_status }}">

                                {{-- TRIP --}}
                                <td>
                                    <div class="cell-asset">
                                        <div>
                                            <div class="asset-name">{{ $trip->trip_id }}</div>
                                            <div class="asset-sub">{{ $trip->trip_type }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- ROUTE --}}
                                <td>
                                    <div class="asset-sub">
                                        {{ $trip->pickup_location }} → {{ $trip->delivery_location }}
                                    </div>
                                </td>

                                {{-- DRIVER --}}
                                <td>
                                    {{ $trip->driver->full_name ?? 'N/A' }}
                                </td>

                                {{-- TRUCK --}}
                                <td>
                                    {{ $trip->truck->truck_number ?? 'N/A' }}
                                </td>

                                {{-- CONTAINER --}}
                                <td>
                                    {{ $trip->container->container_license_number ?? 'N/A' }}
                                </td>

                                {{-- AMOUNT --}}
                                <td>
                                    ${{ $trip->payment_amount ?? 0 }}
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="badge
                                        @if($trip->trip_status == 'active') badge--success
                                        @elseif($trip->trip_status == 'pending') badge--warning
                                        @elseif($trip->trip_status == 'completed') badge--neutral
                                        @else badge--danger @endif">
                                        {{ ucfirst($trip->trip_status) }}
                                    </span>
                                </td>

                                {{-- ACTIONS --}}
                                <td>
                                    <div class="row-actions">

                                        <a href="{{ route('trips.show', $trip->id) }}" class="mini-btn">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>

                                        <a href="{{ route('trips.edit', $trip->id) }}" class="mini-btn">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14" />
                                                <path d="M18.5 2.5l3 3L12 15l-4 1 1-4z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('trips.destroy', $trip->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="mini-btn mini-btn--danger">
                                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14" />
                                                </svg>
                                            </button>
                                        </form>

                                        @php
                                            $trackingUrl = route('trip.track', [
                                                'token' => urlencode(encrypt($trip->id))
                                            ]);
                                        @endphp

                                        <button class="mini-btn copyTrackingBtn" data-link="{{ $trackingUrl }}" type="button">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                                stroke-width="2">

                                                <rect x="9" y="9" width="13" height="13" rx="2" />
                                                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
                                            </svg>
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center;">No trips found</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <div class="meta">
                    Showing {{ $trips->firstItem() ?? 0 }}–{{ $trips->lastItem() ?? 0 }} of {{ $trips->total() }} trips
                </div>
                <div class="pager">
                    {{ $trips->links() }}
                </div>
            </div>

        </div>
    </section>

    {{-- FILTER SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.filters button');
            const rows = document.querySelectorAll('table.data tbody tr');

            buttons.forEach(btn => {
                btn.addEventListener('click', function () {

                    buttons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    const filter = this.dataset.filter;

                    rows.forEach(row => {
                        const status = row.dataset.status;

                        if (filter === 'all' || status === filter) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });

                });
            });
        });
    </script>

    <script>
document.querySelectorAll('.copyTrackingBtn').forEach(btn => {

    btn.addEventListener('click', async function () {

        const link = this.dataset.link;

        await navigator.clipboard.writeText(link);

        this.innerHTML = 'Copied';

        setTimeout(() => {

            this.innerHTML = `
                <svg viewBox="0 0 24 24"
                    width="14"
                    height="14"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">

                    <rect x="9" y="9" width="13" height="13" rx="2"/>
                    <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>
                </svg>
            `;

        }, 1500);

    });

});
</script>

@endsection