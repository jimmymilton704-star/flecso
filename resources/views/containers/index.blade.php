@extends('layouts.app')

@section('title', 'Containers')
@section('body-class', 'page-dashboard')

@section('content')

    <section class="page">
        <div class="page-head">
            <div>
                <div class="breadcrumb">Operations <span>/ Containers</span></div>
                <h1>Containers</h1>
                <div class="page-head__sub">Track ISO 6346 compliant containers across all depots and shipments.</div>
            </div>
            <div class="page-head__actions">
                <a href="{{ route('containers.create') }}" class="btn btn--primary">
                    <svg viewBox="0 0 24 24" width="16" height="16">
                        <path d="M12 5v14M5 12h14"></path>
                    </svg>
                    Add New Container
                </a>
            </div>
        </div>

        {{-- STATS --}}
        <div class="stats-grid">
            <div class="stat">
                <div class="stat__icon stat__icon--green"><svg viewBox="0 0 24 24" width="22" height="22" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <rect x="3" y="7" width="18" height="11" rx="1.5"></rect>
                        <path d="M7 7v11M12 7v11M17 7v11"></path>
                    </svg></div>
                <div class="stat__label">Total Containers</div>
                <div class="stat__value">{{ $containers->count() }}</div>
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34"><path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#10B981" stroke-width="2" fill="none" stroke-linecap="round"></path></svg>
            </div>

            <div class="stat">
                <div class="stat__icon stat__icon--blue"><svg viewBox="0 0 24 24" width="22" height="22" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <rect x="3" y="7" width="18" height="11" rx="1.5"></rect>
                        <path d="M7 7v11M12 7v11M17 7v11"></path>
                    </svg></div>
                <div class="stat__label">Active</div>
                <div class="stat__value">{{ $containers->where('status', 'active')->count() }}</div>
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34"><path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#3B82F6" stroke-width="2" fill="none" stroke-linecap="round"></path></svg>
            </div>

            <div class="stat">
                <div class="stat__icon stat__icon--dark"><svg viewBox="0 0 24 24" width="22" height="22" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <rect x="3" y="7" width="18" height="11" rx="1.5"></rect>
                        <path d="M7 7v11M12 7v11M17 7v11"></path>
                    </svg></div>
                <div class="stat__label">Maintenance</div>
                <div class="stat__value">{{ $containers->where('status', 'maintenance')->count() }}</div>
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34"><path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#111114" stroke-width="2" fill="none" stroke-linecap="round"></path></svg>
            </div>

            <div class="stat">
                <div class="stat__icon stat__icon--orange"><svg viewBox="0 0 24 24" width="22" height="22" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <rect x="3" y="7" width="18" height="11" rx="1.5"></rect>
                        <path d="M7 7v11M12 7v11M17 7v11"></path>
                    </svg></div>
                <div class="stat__label">Inactive</div>
                <div class="stat__value">{{ $containers->where('status', 'inactive')->count() }}</div>
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34"><path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#FF6B1A" stroke-width="2" fill="none" stroke-linecap="round"></path></svg>
            </div>
        </div>

        <div class="card">
            <div class="card__head">
                <div class="toolbar" style="flex:1">
                    <div class="search">
                        <input type="text" placeholder="Search container...">
                    </div>
                    <div class="filters">
                <button class="active" data-filter="all">All</button>
                        <button data-filter="active">Active</button>
                        <button data-filter="inactive">Inactive</button>
                        <button data-filter="maintenance">Maintenance</button>
              </div>
                </div>
            </div>

            <div class="table-wrap">
                <table class="data">
                    <thead>
                        <tr>
                            <th>Container</th>
                            <th>Type</th>
                            <th>ISO Code</th>
                            <th>Owner</th>
                            <th>Weight</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($containers as $container)
                            <tr>
                                <td>
                                    <div class="cell-asset">
                                        <img class="asset-thumb"
                                            src="{{ $container->image ? asset($container->image) : 'https://i.pravatar.cc/30' }}"
                                            alt="">

                                        <div>
                                            <div class="asset-name">{{ $container->container_id }}</div>
                                            <div class="asset-sub">
                                                SN {{ $container->serial_number }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $container->container_type }}</td>

                                <td>
                                    <code style="font-size:12px;background:var(--ink-50);padding:2px 8px;border-radius:6px">
                                                                                    {{ $container->iso_type_size_code }}
                                                                                </code>
                                </td>

                                <td>{{ $container->owner_code }}</td>

                                <td>
                                    <strong>{{ $container->weight_capacity }}</strong>
                                    <span class="muted">kg</span>
                                </td>

                                <td>
                                    @if($container->status == 'active')
                                        <span class="badge badge--success">
                                            <span class="badge-dot"></span>Active
                                        </span>
                                    @elseif($container->status == 'maintenance')
                                        <span class="badge badge--warn">
                                            <span class="badge-dot"></span>Maintenance
                                        </span>
                                    @else
                                        <span class="badge badge--info">
                                            <span class="badge-dot"></span>Inactive
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <div class="row-actions">

                                        {{-- QR BUTTON (we will implement later) --}}


                                        <button class="mini-btn mini-btn--qr" title="QR Code"
                                            onclick="showQR('Container {{ $container->serial_number  }}', '{{ $container->id }}')">

                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <rect x="3" y="3" width="7" height="7" rx="1" />
                                                <rect x="14" y="3" width="7" height="7" rx="1" />
                                                <rect x="3" y="14" width="7" height="7" rx="1" />
                                                <rect x="14" y="17" width="3" height="3" />
                                                <rect x="18" y="14" width="3" height="3" />
                                                <path d="M14 14h3v3M17 20h4M14 17v4" />
                                            </svg>

                                        </button>

                                        <a class="mini-btn" title="View" href="{{ route('containers.show', $container->id) }}">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12Z"></path>
                                                <circle cx="12" cy="12" r="3"></circle>
                                            </svg>
                                        </a>

                                        {{-- EDIT --}}
                                        <a href="{{ route('containers.edit', $container->id) }}" class="mini-btn">
                                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                            </svg>
                                        </a>

                                        {{-- DELETE --}}
                                        <form action="{{ route('containers.destroy', $container->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')

                                            <button class="mini-btn mini-btn--danger"
                                                onclick="return confirm('Delete this container?')">
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
                                <td colspan="7" style="text-align:center;">
                                    No containers found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>
    </section>

    <script src="{{ asset('js/trucks.js') }}"></script>

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