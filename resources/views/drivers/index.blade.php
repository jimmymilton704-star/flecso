@extends('layouts.app')

@section('title', 'Drivers')
@section('body-class', 'page-dashboard')

@section('content')

    <section class="page">
        <div class="page-head">
            <div>
                <div class="breadcrumb">Operations <span>/ Drivers</span></div>
                <h1>Drivers</h1>
                <div class="page-head__sub">Manage drivers, documents, and availability.</div>
            </div>

            <div class="page-head__actions">
                <a href="{{ route('drivers.create') }}" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M12 5v14M5 12h14" />
                    </svg>
                    Add New Driver
                </a>
            </div>
        </div>

        {{-- STATS --}}
        <div class="stats-grid">
            <div class="stat">
                <div class="stat__icon stat__icon--green"><svg viewBox="0 0 24 24" width="22" height="22"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M4 21c0-4 4-7 8-7s8 3 8 7"></path>
                    </svg></div>
                <div class="stat__label">Active</div>
                <div class="stat__value">{{ $drivers->where('status', 'active')->count() }}</div>
                {{-- <div class="stat__trend trend-up">▲ 1.8% vs last month</div> --}}
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34">
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#10B981" stroke-width="2"
                        fill="none" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="stat">
                <div class="stat__icon stat__icon--blue"><svg viewBox="0 0 24 24" width="22" height="22"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M4 21c0-4 4-7 8-7s8 3 8 7"></path>
                    </svg></div>
                <div class="stat__label">Off-Duty</div>
                <div class="stat__value">{{ $drivers->where('status', 'inactive')->count() }}</div>
                {{-- <div class="stat__trend trend-up">▲ 0.4% vs last month</div> --}}
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34">
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#3B82F6" stroke-width="2"
                        fill="none" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="stat">
                <div class="stat__icon stat__icon--dark"><svg viewBox="0 0 24 24" width="22" height="22"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="6" cy="19" r="3"></circle>
                        <circle cx="18" cy="5" r="3"></circle>
                        <path d="M6 16V8a4 4 0 0 1 4-4h4M18 8v8a4 4 0 0 1-4 4h-4"></path>
                    </svg></div>
                <div class="stat__label">On trip</div>
                <div class="stat__value">{{ $drivers->where('status', 'on_trip')->count() }}</div>
                {{-- <div class="stat__trend trend-down">▼ 0.5% vs last month</div> --}}
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34">
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#111114" stroke-width="2"
                        fill="none" stroke-linecap="round"></path>
                </svg>
            </div>
            <div class="stat">
                <div class="stat__icon stat__icon--orange"><svg viewBox="0 0 24 24" width="22" height="22"
                        fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="4"></circle>
                        <path d="M4 21c0-4 4-7 8-7s8 3 8 7"></path>
                    </svg></div>
                <div class="stat__label">License Expiring</div>
                <div class="stat__value">{{ $drivers->where('status', 'suspended')->count() }}</div>
                {{-- <div class="stat__trend trend-down">▼ 1.2% vs last month</div> --}}
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34">
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#FF6B1A" stroke-width="2"
                        fill="none" stroke-linecap="round"></path>
                </svg>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="card">

            <div class="card__head">
                <div class="toolbar" style="flex:1">

                    <div class="search">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-3-3" />
                        </svg>
                        <input type="text" placeholder="Search by name, email or phone…" />
                    </div>

                    <div class="filters">
                        <button class="active" data-filter="all">All</button>
                        <button data-filter="active">Active</button>
                        <button data-filter="inactive">Inactive</button>
                        <button data-filter="on_trip">On Trip</button>
                    </div>

                </div>
            </div>

            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Driver</th>
                            <th>Contact</th>
                            <th>Licence</th>
                            <th>Expiry</th>
                            {{-- <th>Rating</th> --}}
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($drivers as $driver)
                            <tr  data-status="{{ $driver->status }}">
                                <td>
                                    <div class="cell-asset">
                                        <img class="asset-thumb"
                                            src="{{ asset($driver->driver_photo ?? 'https://via.placeholder.com/40') }}"
                                            alt="">
                                        <div>
                                            <div class="asset-name">{{ $driver->full_name }}</div>
                                            <div class="asset-sub">ID: {{ $driver->id }}</div>
                                        </div>
                                    </div>
                                </td>


                                <td>{{ $driver->phone }}</td>
                                <td>{{ $driver->license_number }}</td>
                                <td>{{ $driver->license_expiry }}</td>
                                {{-- <td>{{ $driver->rating }}</td> --}}



                                <td>
                                    <span
                                        class="badge 
                                    @if ($driver->status == 'active') badge--success
                                    @elseif($driver->status == 'inactive') badge--neutral
                                    @elseif($driver->status == 'on_trip') badge--warning
                                    @else badge--danger @endif">
                                        {{ ucfirst(str_replace('_', ' ', $driver->status)) }}
                                    </span>
                                </td>



                                <td>
                                    <div class="row-actions">

                                        {{-- VIEW --}}
                                        <a href="{{ route('drivers.show', $driver->id) }}" class="mini-btn">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </a>

                                        {{-- EDIT --}}
                                        <a href="{{ route('drivers.edit', $driver->id) }}" class="mini-btn">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                            </svg>
                                        </a>

                                        {{-- DELETE --}}
                                        <form action="{{ route('drivers.delete', $driver->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="mini-btn mini-btn--danger">
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
                                <td colspan="6" style="text-align:center;">No drivers found</td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <div class="meta">
                    Showing {{ $drivers->firstItem() ?? 0 }}–{{ $drivers->lastItem() ?? 0 }} of {{ $drivers->total() }}
                    drivers
                </div>
                <div class="pager">
                    {{ $drivers->links() }}
                </div>
            </div>

        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.filters button');
            const rows = document.querySelectorAll('table.data tbody tr');

            buttons.forEach(btn => {
                btn.addEventListener('click', function() {

                    // active class handling
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

@endsection
