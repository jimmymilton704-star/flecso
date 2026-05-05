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
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14"/>
                </svg>
                Add New Driver
            </a>
        </div>
    </div>

    {{-- STATS --}}
    <div class="stats-grid">

        <div class="stat">
            <div class="stat__icon stat__icon--green">👤</div>
            <div class="stat__label">Active</div>
            <div class="stat__value">{{ $drivers->where('status','active')->count() }}</div>
        </div>

        <div class="stat">
            <div class="stat__icon stat__icon--orange">⏳</div>
            <div class="stat__label">Inactive</div>
            <div class="stat__value">{{ $drivers->where('status','inactive')->count() }}</div>
        </div>

        <div class="stat">
            <div class="stat__icon stat__icon--blue">🚚</div>
            <div class="stat__label">On Trip</div>
            <div class="stat__value">{{ $drivers->where('status','on_trip')->count() }}</div>
        </div>

        <div class="stat">
            <div class="stat__icon stat__icon--dark">⚠️</div>
            <div class="stat__label">Suspended</div>
            <div class="stat__value">{{ $drivers->where('status','suspended')->count() }}</div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="card">

        <div class="card__head">
            <div class="toolbar" style="flex:1">

                <div class="search">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="7"/>
                        <path d="m20 20-3-3"/>
                    </svg>
                    <input type="text" placeholder="Search by name, email or phone…" />
                </div>

                <div class="filters">
                    <button class="active">All</button>
                    <button>Active</button>
                    <button>Inactive</button>
                    <button>On Trip</button>
                </div>

            </div>
        </div>

        <div class="table-wrap">
            <table class="data">
                <thead>
                    <tr>
                        <th>Driver</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Documents</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($drivers as $driver)
                        <tr>
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

                            <td>{{ $driver->email }}</td>
                            <td>{{ $driver->phone }}</td>

                            <td>
                                <span class="badge 
                                    @if($driver->status=='active') badge--success
                                    @elseif($driver->status=='inactive') badge--neutral
                                    @elseif($driver->status=='on_trip') badge--warning
                                    @else badge--danger @endif">
                                    {{ ucfirst(str_replace('_',' ',$driver->status)) }}
                                </span>
                            </td>

                            <td>
                                <div style="display:flex;gap:6px;">
                                    
                                    @if($driver->license_front)
                                        <a href="{{ asset($driver->license_front) }}" target="_blank" title="License">
                                            📄
                                        </a>
                                    @endif

                                    @if($driver->cqc_card)
                                        <a href="{{ asset($driver->cqc_card) }}" target="_blank" title="CQC">
                                            📄
                                        </a>
                                    @endif

                                    @if($driver->medical_certificate)
                                        <a href="{{ asset($driver->medical_certificate) }}" target="_blank" title="Medical">
                                            📄
                                        </a>
                                    @endif

                                </div>
                            </td>

                            <td>
                                <div class="row-actions">

                                    {{-- VIEW --}}
                                    <a href="{{ route('drivers.show',$driver->id) }}" class="mini-btn">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('drivers.edit',$driver->id) }}" class="mini-btn">
                                        <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z"/>
                                        </svg>
                                    </a>

                                    {{-- DELETE --}}
                                    <form action="{{ route('drivers.delete',$driver->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="mini-btn mini-btn--danger">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M6 6l1 14a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2l1-14"/>
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
                Showing {{ $drivers->firstItem() ?? 0 }}–{{ $drivers->lastItem() ?? 0 }} of {{ $drivers->total() }} drivers
            </div>
            <div class="pager">
                {{ $drivers->links() }}
            </div>
        </div>

    </div>
</section>

@endsection