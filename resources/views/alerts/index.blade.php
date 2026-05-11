@extends('layouts.app')

@section('title', 'SOS Alerts')
@section('body-class', 'page-dashboard')

@section('content')

    <section class="page">

        <div class="page-head">
            <div>
                <div class="breadcrumb">Operations <span>/ SOS Alerts</span></div>
                <h1>SOS Alerts <span class="sos-live-pulse"></span></h1>
                <div class="page-head__sub">
                    Real-time emergency alerts from drivers and vehicles — respond and coordinate from here.
                </div>
            </div>
        </div>

        {{-- ===================== STATS ===================== --}}
        <div class="stats-grid">

            <div class="stat">
                <div class="stat__icon" style="background:var(--danger-50);color:var(--danger-700)">
                    ⚠️
                </div>
                <div class="stat__label">Total Pending</div>
                <div class="stat__value">
                    {{ $alerts->where('status', 'pending')->count() }}
                </div>
            </div>

            <div class="stat">
                <div class="stat__icon" style="background:var(--warn-50);color:#8A5100">
                    ⏳
                </div>
                <div class="stat__label">Pending Today</div>
                <div class="stat__value">
                    {{ $alerts->where('status', 'pending')->where('created_at', '>=', now()->startOfDay())->count() }}
                </div>
            </div>

            <div class="stat">
                <div class="stat__icon stat__icon--green">
                    ✔
                </div>
                <div class="stat__label">Resolved Today</div>
                <div class="stat__value">
                    {{ $alerts->where('status', 'resolved')->where('updated_at', '>=', now()->startOfDay())->count() }}
                </div>
            </div>

            <div class="stat">
                <div class="stat__icon stat__icon--orange">
                    ⏱
                </div>
                <div class="stat__label">Total Alerts</div>
                <div class="stat__value">
                    {{ $alerts->total() }}
                </div>
            </div>

        </div>

        {{-- ===================== TABLE ===================== --}}
        <div class="card">
            <div class="card__head">
                <div class="toolbar" style="flex:1">

                    <div class="filters">

                        <button type="button" class="filter-btn {{ request('alert_source') == '' ? 'active' : '' }}"
                            data-filter="">
                            All
                        </button>

                        <button type="button" class="filter-btn {{ request('alert_source') == 'sos' ? 'active' : '' }}"
                            data-filter="sos">
                            SOS
                        </button>

                        <button type="button" class="filter-btn {{ request('alert_source') == 'fleet' ? 'active' : '' }}"
                            data-filter="fleet">
                            Fleet
                        </button>

                        <button type="button" class="filter-btn {{ request('alert_source') == 'fuel' ? 'active' : '' }}"
                            data-filter="fuel">
                            Fuel
                        </button>

                    </div>

                </div>
            </div>

            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Alert</th>
                            <th>Type</th>
                            <th>Driver · Truck</th>
                            <th>Location</th>
                            <th>Raised</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($alerts as $alert)
                            <tr>

                                {{-- ALERT ID --}}
                                <td>

                                    @if ($alert->alert_source == 'sos')
                                        #SOS-{{ $alert->id }}
                                    @elseif ($alert->alert_source == 'fleet')
                                        #FLT-{{ $alert->id }}
                                    @elseif ($alert->alert_source == 'fuel')
                                        #FUEL-{{ $alert->id }}
                                    @endif

                                </td>
                                {{-- TYPE --}}
                                <td>

                                    @if ($alert->alert_source == 'sos')
                                        <span class="badge badge--danger">
                                            SOS · {{ ucfirst($alert->emergency_type) }}
                                        </span>
                                    @elseif ($alert->alert_source == 'fleet')
                                        <span class="badge badge--warn">
                                            Fleet · {{ ucfirst($alert->alert_type) }}
                                        </span>
                                    @elseif ($alert->alert_source == 'fuel')
                                        <span class="badge badge--orange">
                                            Fuel · {{ ucfirst($alert->alert_type ?? 'Alert') }}
                                        </span>
                                    @endif

                                </td>
                                {{-- DRIVER / TRUCK --}}
                                <td>

                                    @if ($alert->alert_source == 'sos')
                                        {{ $alert->driver->full_name ?? 'N/A' }}

                                        <br>

                                        <small>
                                            {{ $alert->trip->truck->truck_number ?? 'No Truck' }}
                                        </small>
                                    @elseif ($alert->alert_source == 'fleet')
                                        Fleet System

                                        <br>

                                        <small>
                                            {{ $alert->truck->truck_number ?? 'No Truck' }}
                                        </small>
                                    @elseif ($alert->alert_source == 'fuel')
                                        {{ $alert->driver->full_name ?? 'N/A' }}

                                        <br>

                                        <small>
                                            {{ $alert->truck->truck_number ?? 'No Truck' }}
                                        </small>
                                    @endif

                                </td>

                                {{-- LOCATION --}}
                                <td>

                                    @if ($alert->alert_source == 'sos')
                                        {{ $alert->location ?? 'Unknown' }}
                                    @elseif ($alert->alert_source == 'fleet')
                                        {{ $alert->location ?? 'Fleet Monitoring' }}
                                    @elseif ($alert->alert_source == 'fuel')
                                        {{ $alert->station_name ?? 'Fuel Station' }}
                                    @endif

                                </td>

                                {{-- TIME --}}
                                <td>
                                    {{ $alert->created_at->diffForHumans() }}
                                </td>

                                {{-- STATUS --}}
                                <td>

                                    @if ($alert->status == 'pending')
                                        <span style="color:#EF4444;font-weight:600">
                                            ● Pending
                                        </span>
                                    @else
                                        <span style="color:#10B981;font-weight:600">
                                            ● Resolved
                                        </span>
                                    @endif

                                </td>

                                {{-- ACTIONS --}}
                                <td>

                                    @if ($alert->alert_source == 'sos')
                                        <a href="{{ route('sos.show', $alert->id) }}" class="btn btn--sm btn--ghost">
                                            View
                                        </a>

                                        @if ($alert->status == 'pending')
                                            <form action="{{ route('sos.resolve') }}" method="POST"
                                                style="display:inline;">

                                                @csrf

                                                <input type="hidden" name="alert_id" value="{{ $alert->id }}">
                                                <input type="hidden" name="source" value="{{ $alert->alert_source }}">

                                                <button class="btn btn--sm btn--primary">
                                                    Resolve
                                                </button>

                                            </form>
                                        @endif
                                    @elseif ($alert->alert_source == 'fleet')
                                        <a href="{{ route('fleet.show', $alert->id) }}" class="btn btn--sm btn--ghost">
                                            View
                                        </a>
                                        @if ($alert->is_read == 0)
                                            <form action="{{ route('sos.resolve') }}" method="POST"
                                                style="display:inline;">

                                                @csrf

                                                <input type="hidden" name="alert_id" value="{{ $alert->id }}">
                                                <input type="hidden" name="source" value="{{ $alert->alert_source }}">

                                                <button class="btn btn--sm btn--primary">
                                                    Resolve
                                                </button>

                                            </form>
                                        @endif
                                    @elseif ($alert->alert_source == 'fuel')
                                        <a href="{{ route('fuel.show', $alert->id) }}" class="btn btn--sm btn--ghost">
                                            View
                                        </a>`


                                        @if ($alert->is_resolved == 0)
                                            <form action="{{ route('sos.resolve') }}" method="POST" style="display:inline;">

                                                @csrf

                                                <input type="hidden" name="alert_id" value="{{ $alert->id }}">
                                                <input type="hidden" name="source" value="{{ $alert->alert_source }}">

                                                <button class="btn btn--sm btn--primary">
                                                    Resolve
                                                </button>

                                            </form>
                                        @endif
                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" style="text-align:center;padding:20px;">
                                    No alerts found
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            {{-- ===================== PAGINATION ===================== --}}
            <div class="pagination">
                <div class="meta">
                    Showing {{ $alerts->firstItem() ?? 0 }} to {{ $alerts->lastItem() ?? 0 }} of {{ $alerts->total() }}
                </div>

                <div class="pager">
                    {{ $alerts->links() }}
                </div>
            </div>

        </div>

    </section>
    <script>
        document.querySelectorAll('.filter-btn').forEach(button => {

            button.addEventListener('click', function() {
                const filter = this.dataset.filter;
                const url = new URL(window.location.href);
                if (filter === '') {
                    url.searchParams.delete('alert_source');
                } else {
                    url.searchParams.set('alert_source', filter);
                }
                window.location.href = url.toString();

            });

        });
    </script>
@endsection
