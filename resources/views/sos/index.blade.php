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

                <form method="GET" class="filters">
                    <button type="submit" name="status" value="" class="{{ request('status') == '' ? 'active' : '' }}">All</button>
                    <button type="submit" name="status" value="pending" class="{{ request('status') == 'pending' ? 'active' : '' }}">Pending</button>
                    <button type="submit" name="status" value="resolved" class="{{ request('status') == 'resolved' ? 'active' : '' }}">Resolved</button>
                </form>

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
                            <td>#SOS-{{ $alert->id }}</td>

                            {{-- TYPE --}}
                            <td>{{ ucfirst($alert->emergency_type) }}</td>

                            {{-- DRIVER + TRUCK --}}
                            <td>
                                {{ $alert->driver->full_name ?? 'N/A' }}
                                <br>
                                <small>
                                    {{ $alert->trip->truck->truck_number ?? 'No Truck' }}
                                </small>
                            </td>

                            {{-- LOCATION --}}
                            <td>{{ $alert->location ?? 'Unknown' }}</td>

                            {{-- TIME --}}
                            <td>{{ $alert->created_at->diffForHumans() }}</td>

                            {{-- STATUS --}}
                            <td>
                                @if($alert->status == 'pending')
                                    <span style="color:#EF4444;font-weight:600">● Pending</span>
                                @else
                                    <span style="color:#10B981;font-weight:600">● Resolved</span>
                                @endif
                            </td>

                            {{-- ACTIONS --}}
                            <td>

                                <a href="{{ route('sos.show', $alert->id) }}" class="btn btn--sm btn--ghost">
                                    View
                                </a>

                                @if($alert->status == 'pending')
                                    <form action="{{ route('sos.resolve') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <input type="hidden" name="sos_id" value="{{ $alert->id }}">
                                        <button class="btn btn--sm btn--primary">
                                            Resolve
                                        </button>
                                    </form>
                                @endif

                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center;padding:20px;">
                                No SOS alerts found
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

@endsection