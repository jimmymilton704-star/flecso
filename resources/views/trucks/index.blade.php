@extends('layouts.app')

@section('title', 'Truck')
@section('body-class', 'page-dashboard')

@section('content')
<style>
/* Pagination Fix */
.pagination {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    padding: 18px 20px;
    border-top: 1px solid #f0f0f0;
    background: #ffffff;
}

.pagination .meta {
    font-size: 13px;
    color: #6b7280;
    white-space: nowrap;
}

.pagination .pager {
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.pagination .pager nav {
    display: flex;
    align-items: center;
    justify-content: flex-end;
}

.pagination .pager > nav > div:first-child {
    display: none;
}

.pagination .pager > nav > div:last-child {
    display: flex;
    align-items: center;
    gap: 6px;
}

.pagination .pager span,
.pagination .pager a {
    width: 34px;
    height: 34px;
    min-width: 34px;
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    border: 1px solid #e5e7eb;
    background: #ffffff;
    color: #111827;
    font-size: 13px;
    font-weight: 500;
    text-decoration: none;
    line-height: 1;
    transition: all 0.2s ease;
}

.pagination .pager a:hover {
    background: #fff4ee;
    border-color: #f15e2e;
    color: #f15e2e;
}

.pagination .pager span[aria-current="page"] span {
    background: #f15e2e !important;
    border-color: #f15e2e !important;
    color: #ffffff !important;
}

.pagination .pager svg {
    width: 16px !important;
    height: 16px !important;
    max-width: 16px !important;
    max-height: 16px !important;
    display: block;
}

.pagination .pager p {
    display: none;
}

/* Disabled Previous / Next */
.pagination .pager span[aria-disabled="true"] span {
    color: #9ca3af;
    background: #f9fafb;
    border-color: #e5e7eb;
    cursor: not-allowed;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .pagination {
        flex-direction: column;
        align-items: flex-start;
    }

    .pagination .pager {
        width: 100%;
        justify-content: flex-start;
        overflow-x: auto;
        padding-bottom: 4px;
    }
}
</style>

    <section class="page">
        <div class="page-head">
            <div>
                <div class="breadcrumb">Operations <span>/ Trucks</span></div>
                <h1>Trucks</h1>
                <div class="page-head__sub">Manage your entire fleet, monitor status, and schedule maintenance.</div>
            </div>
            <div class="page-head__actions">
                <button class="btn btn--ghost">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" />
                    </svg>
                    Export CSV
                </button>

                <a href="{{ route('trucks.create') }}" class="btn btn--primary" id="addTruckBtn">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Add New Truck
                </a>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat">
                <div class="stat__icon stat__icon--orange">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 7h10v10H3z" />
                        <path d="M13 10h5l3 3v4h-8" />
                        <circle cx="7" cy="18" r="2" />
                        <circle cx="17" cy="18" r="2" />
                    </svg>
                </div>
                <div class="stat__label">Active</div>
                <div class="stat__value">{{ $trucks->where('status', 'active')->count() }}</div>
                {{-- <div class="stat__trend trend-up">▲ 3.2% vs last month</div> --}}
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34">
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#FF6B1A" stroke-width="2"
                        fill="none" stroke-linecap="round" />
                </svg>
            </div>

            <div class="stat">
                <div class="stat__icon stat__icon--dark">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="m12 2 2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z" />
                    </svg>
                </div>
                <div class="stat__label">Maintenance</div>
                <div class="stat__value"> {{ $trucks->where('status', 'maintenance')->count() }}</div>
                {{-- <div class="stat__trend trend-down">▼ 1.5% vs last month</div> --}}
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34">
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#111114" stroke-width="2"
                        fill="none" stroke-linecap="round" />
                </svg>
            </div>

            <div class="stat">
                <div class="stat__icon stat__icon--blue">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 7h10v10H3z" />
                        <path d="M13 10h5l3 3v4h-8" />
                        <circle cx="7" cy="18" r="2" />
                        <circle cx="17" cy="18" r="2" />
                    </svg>
                </div>
                <div class="stat__label">Idle</div>
                <div class="stat__value">{{ $trucks->where('status', 'idle')->count() }}</div>
                {{-- <div class="stat__trend trend-up">▲ 0.8% vs last month</div> --}}
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34">
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#3B82F6" stroke-width="2"
                        fill="none" stroke-linecap="round" />
                </svg>
            </div>

            <div class="stat">
                <div class="stat__icon stat__icon--green">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M3 7h10v10H3z" />
                        <path d="M13 10h5l3 3v4h-8" />
                        <circle cx="7" cy="18" r="2" />
                        <circle cx="17" cy="18" r="2" />
                    </svg>
                </div>
                <div class="stat__label">Inactive</div>
                <div class="stat__value">{{ $trucks->where('status', 'inactive')->count() }}</div>
                {{-- <div class="stat__trend trend-down">▼ 0.4% vs last month</div> --}}
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34">
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#10B981" stroke-width="2"
                        fill="none" stroke-linecap="round" />
                </svg>
            </div>
        </div>

        <div class="card">
            <div class="card__head">
                <div class="toolbar" style="flex:1">
                    <div class="search">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3-3" />
                        </svg>
                        <input type="text" id="truckSearch" placeholder="Search by truck ID, plate, or driver…" />
                    </div>
                    <div class="filters">
                        <button class="active" data-filter="all">All</button>
                        <button data-filter="active">Active</button>
                        <button data-filter="maintenance">Maintenance</button>
                        <button data-filter="idle">Idle</button>
                        <button data-filter="inactive">Inactive</button>
                    </div>
                </div>
                <button class="btn btn--ghost btn--sm">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z" />
                    </svg>
                    Filters
                </button>
            </div>

            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Truck</th>
                            <th>Category</th>
                            <th>Capacity</th>
                            <th>Assigned Driver</th>
                            <th>No of Axles</th>
                            <th>Next inspection data</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody id="trucksBody">
                        @forelse($trucks as $truck)
                            <tr data-status="{{ strtolower($truck->status) }}"
                                data-search="{{ strtolower($truck->license_plate_number . ' ' . $truck->truck_license_number . ' ' . optional($truck->driver)->name . ' ' . $truck->truck_type_category) }}">
                                <td>
                                    <div class="cell-asset">
                                        <img class="asset-thumb" src="{{ $truck->image }}" alt="">
                                        <div>
                                            <div class="asset-name">{{ $truck->license_plate_number }}</div>
                                            <div class="asset-sub">{{ $truck->truck_license_number }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $truck->truck_type_category ?? '-' }}</td>

                                <td>{{ $truck->capacity_tons ?? '-' }}</td>

                                <td>{{ $truck->driver->full_name ?? 'Not Assigned' }}</td>

                                <td>{{ $truck->number_of_axles ?? '-' }}</td>

                                <td>{{ $truck->next_inspection_date ?? '-' }}</td>

                                <td>
                                    <span
                                        class="badge
                                    @if ($truck->status == 'active') badge--success
                                    @elseif($truck->status == 'Maintenance') badge--warning
                                    @elseif($truck->status == 'idle') badge--neutral
                                    @else badge--danger @endif">
                                        {{ ucfirst(str_replace('_', ' ', $truck->status)) }}
                                    </span>
                                </td>

                                <td>
                                    <div class="row-actions">

                                        <button class="mini-btn mini-btn--qr" title="QR Code"
                                            onclick="showQR('Truck {{ $truck->license_plate_number }}', '{{ $truck->id }}')">

                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <rect x="3" y="3" width="7" height="7" rx="1" />
                                                <rect x="14" y="3" width="7" height="7" rx="1" />
                                                <rect x="3" y="14" width="7" height="7" rx="1" />
                                                <rect x="14" y="17" width="3" height="3" />
                                                <rect x="18" y="14" width="3" height="3" />
                                                <path d="M14 14h3v3M17 20h4M14 17v4" />
                                            </svg>

                                        </button>

                                        <button class="mini-btn" title="View">
                                            <a href="{{ route('trucks.show', $truck->id) }}">

                                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z" />
                                                    <circle cx="12" cy="12" r="3" />
                                                </svg>
                                            </a>
                                        </button>

                                        <button class="mini-btn" title="Edit">
                                            <a href="{{ route('trucks.edit', $truck->id) }}">

                                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                    <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                                </svg>
                                            </a>
                                        </button>

                                        <form action="{{ route('trucks.destroy', $truck->id) }}"
                                            method="POST"style="display:inline;">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="mini-btn mini-btn--danger" title="Delete">
                                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path
                                                        d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14" />
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" style="text-align:center;">No trucks found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <div class="meta">
                    Showing {{ $trucks->firstItem() ?? 0 }}–{{ $trucks->lastItem() ?? 0 }} of {{ $trucks->total() }}
                    trucks
                </div>

                <div class="pager">
                    {{ $trucks->onEachSide(1)->links() }}
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const buttons = document.querySelectorAll('.filters button');
            const rows = document.querySelectorAll('#trucksBody tr');
            const searchInput = document.getElementById('truckSearch');

            let currentFilter = 'all';
            let searchValue = '';

            // FILTER BUTTONS
            buttons.forEach(btn => {
                btn.addEventListener('click', function() {

                    buttons.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    currentFilter = this.dataset.filter;
                    applyFilter();

                });
            });

            // SEARCH INPUT
            searchInput.addEventListener('input', function() {
                searchValue = this.value.toLowerCase();
                applyFilter();
            });

            // MAIN FILTER FUNCTION
            function applyFilter() {

                rows.forEach(row => {

                    const status = (row.dataset.status || '').toLowerCase();
                    const search = (row.dataset.search || '').toLowerCase();

                    const matchFilter = (currentFilter === 'all' || status === currentFilter);
                    const matchSearch = search.includes(searchValue);

                    if (matchFilter && matchSearch) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }

                });

            }

        });
    </script>
    <script src="{{ asset('js/trucks.js') }}"></script>
@endsection
