{{--
  truck-detail.blade.php
  Extends your main layout. Only the <section> content + QR modal.

  Controller should pass:
    $truck   — Truck model (id, image, plate, category, fuel, status, capacity,
                             mileage, last_service, driver, vin, registration_date,
                             usage_type, registration_country, from_lat, from_lng)
    $driver  — Driver model|null (name, avatar, id, rating, trips)
    $maintenanceHistory — Collection of {date, description, cost}
--}}

@extends('layouts.app')

@section('title', $truck->id . ' · Truck — Flecso')

{{-- @push('styles') --}}
<link rel="stylesheet" href="{{ asset('css/trucks.css') }}" />
<link rel="stylesheet" href="{{ asset('css/detail.css') }}" />
{{-- @endpush --}}

@section('content')

    @php
        $mileageRaw = (int) preg_replace('/\D/', '', $truck->mileage);
        $serviceDoneKm = $mileageRaw - 8000;
        $serviceDueKm = $mileageRaw + 12000;
    @endphp

    {{-- ══════════════════════════════════════════════════════
            SECTION
        ══════════════════════════════════════════════════════ --}}
    <section class="page" id="detailRoot">

        {{-- Back --}}
        <a href="{{ route('trucks.index') }}" class="detail-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 7h10v10H3z" />
                <path d="M13 10h5l3 3v4h-8" />
                <circle cx="7" cy="18" r="2" />
                <circle cx="17" cy="18" r="2" />
            </svg>
            Back to Trucks
        </a>

        {{-- ── Hero ─────────────────────────────────────────── --}}
        <div class="detail-hero">
            <img class="detail-hero__img" src="{{ asset($truck->image) }}" alt="{{ $truck->id }}" />

            <div class="detail-hero__body">
                <div class="detail-hero__meta">
                    <span class="detail-hero__id">Truck ID</span>

                    @switch($truck->status)
                        @case('active')
                            <span class="badge badge--success">Active</span>
                        @break

                        @case('in-transit')
                            <span class="badge badge--warning">In Transit</span>
                        @break

                        @case('maintenance')
                            <span class="badge badge--danger">Maintenance</span>
                        @break

                        @case('idle')
                            <span class="badge badge--neutral">Idle</span>
                        @break

                        @default
                            <span class="badge badge--neutral">{{ ucfirst($truck->status) }}</span>
                    @endswitch

                    <span class="badge badge--neutral">{{ $truck->category }}</span>
                    <span class="badge badge--orange">{{ $truck->fuel }}</span>
                </div>

                <h1>{{ $truck->id }}</h1>

                <div class="detail-hero__sub">
                    <span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
                        </svg>
                        Plate {{ $truck->plate }}
                    </span>
                    {{-- <span>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-7 8-7s8 3 8 7"/>
          </svg>
          {{ $driver?->name ?? '—' }}
        </span> --}}
                    <span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                        </svg>
                        {{ $truck->capacity }} tons capacity
                    </span>
                </div>
            </div>

            <div class="detail-hero__actions">
                <button class="btn btn--ghost" id="openQrBtn" data-qr-id="{{ $truck->id }}"
                    data-qr-title="Truck {{ $truck->id }}" data-qr-subtitle="{{ $truck->plate }}">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="3" y="3" width="5" height="5" rx="1" />
                        <rect x="16" y="3" width="5" height="5" rx="1" />
                        <rect x="3" y="16" width="5" height="5" rx="1" />
                        <path d="M21 16h-3v3M21 21h-3M16 16v5M11 3v3M11 8v3M3 11h5M11 11h3M16 11h2M3 16h2M3 21h2M7 16v5" />
                    </svg>
                    QR Code
                </button>
                <a href="{{ route('trucks.edit', $truck->id) }}" class="btn btn--ghost">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z" />
                    </svg>
                    Edit
                </a>
                <button class="btn btn--primary">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M12 2v4M5 7l2.5 2.5M19 7l-2.5 2.5M12 22a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
                        <path d="m8 13 4-4 4 4-4 4z" />
                    </svg>
                    Assign Trip
                </button>
            </div>
        </div>

        {{-- ── Quick Stats ─────────────────────────────────── --}}
        <div class="detail-quickstats">
            <div class="qs">
                <div class="qs__label">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                    </svg>
                    Capacity
                </div>
                <div class="qs__value">{{ $truck->capacity }} t</div>
                <div class="qs__sub">Max payload 18,000 kg</div>
            </div>

            <div class="qs">
                <div class="qs__label">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M12 2v4M5 7l2.5 2.5M19 7l-2.5 2.5M12 22a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
                        <path d="m8 13 4-4 4 4-4 4z" />
                    </svg>
                    Mileage
                </div>
                <div class="qs__value">{{ $truck->mileage }}</div>
                <div class="qs__sub">+2,410 km this month</div>
            </div>

            <div class="qs">
                <div class="qs__label">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <path d="M16 2v4M8 2v4M3 10h18" />
                    </svg>
                    Last Service
                </div>
                <div class="qs__value" style="font-size:17px">{{ $truck->last_service }}</div>
                <div class="qs__sub">At {{ number_format($serviceDoneKm) }} km</div>
            </div>

            <div class="qs">
                <div class="qs__label">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    Next Service Due
                </div>
                <div class="qs__value" style="font-size:17px">In 12,000 km</div>
                <div class="qs__sub">≈ {{ number_format($serviceDueKm) }} km</div>
            </div>
        </div>

        {{-- ── Main Grid ────────────────────────────────────── --}}
        <div class="detail-grid">

            {{-- LEFT COLUMN --}}
            <div>

                {{-- Tabs --}}
                <div class="detail-tabs" role="tablist">
                    <button class="active" data-pane="overview">Overview</button>
                    <button data-pane="specs">Technical Specs</button>
                    <button data-pane="compliance">Compliance</button>
                    <button data-pane="maintenance">Maintenance</button>
                </div>

                {{-- ── Pane: Overview ── --}}
                <div class="detail-pane active" data-pane="overview">

                    <div class="card">
                        <div class="card__head">
                            <div class="card__title">
                                <h3>Basic Information</h3>
                            </div>
                        </div>
                        <div class="card__body">
                            <div class="info-grid">
                                <div class="info-row">
                                    <span class="info-row__key">Truck Number</span>
                                    <span class="info-row__val">{{ $truck->id }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">License Plate</span>
                                    <span class="info-row__val"><code>{{ $truck->plate }}</code></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Category</span>
                                    <span class="info-row__val">{{ $truck->category }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Capacity</span>
                                    <span class="info-row__val">{{ $truck->capacity }} tons</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Fuel Type</span>
                                    <span class="info-row__val">{{ $truck->fuel }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Status</span>
                                    <span class="info-row__val">
                                        @switch($truck->status)
                                            @case('active')
                                                <span class="badge badge--success">Active</span>
                                            @break

                                            @case('in-transit')
                                                <span class="badge badge--warning">In Transit</span>
                                            @break

                                            @case('maintenance')
                                                <span class="badge badge--danger">Maintenance</span>
                                            @break

                                            @default
                                                <span class="badge badge--neutral">{{ ucfirst($truck->status) }}</span>
                                        @endswitch
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card" style="margin-top:14px">
                        <div class="card__head">
                            <div class="card__title">
                                <h3>Identity &amp; Legal</h3>
                            </div>
                        </div>
                        <div class="card__body">
                            <div class="info-grid">
                                <div class="info-row">
                                    <span class="info-row__key">VIN / Chassis No.</span>
                                    <span class="info-row__val"><code>{{ $truck->vin ?? '—' }}</code></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Registration Date</span>
                                    <span class="info-row__val">
                                        {{ $truck->registration_date ? \Carbon\Carbon::parse($truck->registration_date)->format('Y-m-d') : '—' }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Usage Type</span>
                                    <span class="info-row__val">{{ $truck->usage_type ?? 'Owned' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Country of Reg.</span>
                                    <span class="info-row__val">{{ $truck->registration_country ?? '—' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>{{-- /overview --}}

                {{-- ── Pane: Technical Specs ── --}}
                <div class="detail-pane" data-pane="specs">
                    <div class="card">
                        <div class="card__head">
                            <div class="card__title">
                                <h3>Technical Specifications</h3>
                            </div>
                        </div>
                        <div class="card__body">
                            <div class="info-grid">
                                <div class="info-row"><span class="info-row__key">Vehicle Category</span><span
                                        class="info-row__val">{{ $truck->vehicle_category ?? 'N3 (Heavy)' }}</span></div>
                                <div class="info-row"><span class="info-row__key">Gross Vehicle Weight</span><span
                                        class="info-row__val">{{ $truck->gvw ? number_format($truck->gvw) . ' kg' : '26,000 kg' }}</span>
                                </div>
                                <div class="info-row"><span class="info-row__key">Payload Capacity</span><span
                                        class="info-row__val">{{ $truck->payload ? number_format($truck->payload) . ' kg' : '18,000 kg' }}</span>
                                </div>
                                <div class="info-row"><span class="info-row__key">Number of Axles</span><span
                                        class="info-row__val">{{ $truck->axles ?? '3' }}</span></div>
                                <div class="info-row"><span class="info-row__key">Euro Class</span><span
                                        class="info-row__val">{{ $truck->euro_class ?? 'Euro VI' }}</span></div>
                                <div class="info-row"><span class="info-row__key">Fuel Type</span><span
                                        class="info-row__val">{{ $truck->fuel }}</span></div>
                                <div class="info-row"><span class="info-row__key">Transmission</span><span
                                        class="info-row__val">{{ $truck->transmission ?? 'Automatic 12-speed' }}</span>
                                </div>
                                <div class="info-row"><span class="info-row__key">Engine Power</span><span
                                        class="info-row__val">{{ $truck->engine_power ?? '480 hp · 353 kW' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>{{-- /specs --}}

                {{-- ── Pane: Compliance ── --}}
                <div class="detail-pane" data-pane="compliance">
                    <div class="card">
                        <div class="card__head">
                            <div class="card__title">
                                <h3>Compliance &amp; Documents</h3>
                            </div>
                        </div>
                        <div class="card__body">
                            <div class="info-grid">
                                <div class="info-row">
                                    <span class="info-row__key">Last Inspection</span>
                                    <span class="info-row__val">
                                        {{ $truck->last_inspection ? \Carbon\Carbon::parse($truck->last_inspection)->format('Y-m-d') : '—' }}
                                        <span class="badge badge--success" style="margin-left:6px">Passed</span>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Next Inspection</span>
                                    <span class="info-row__val">
                                        {{ $truck->next_inspection ? \Carbon\Carbon::parse($truck->next_inspection)->format('Y-m-d') : '—' }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Insurance Provider</span>
                                    <span class="info-row__val">{{ $truck->insurance_provider ?? '—' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Insurance Expiry</span>
                                    <span class="info-row__val">
                                        {{ $truck->insurance_expiry ? \Carbon\Carbon::parse($truck->insurance_expiry)->format('Y-m-d') : '—' }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Tachograph Expiry</span>
                                    <span class="info-row__val">
                                        {{ $truck->tachograph_expiry ? \Carbon\Carbon::parse($truck->tachograph_expiry)->format('Y-m-d') : '—' }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Road Tax Expiry</span>
                                    <span class="info-row__val">
                                        {{ $truck->road_tax_expiry ? \Carbon\Carbon::parse($truck->road_tax_expiry)->format('Y-m-d') : '—' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Documents --}}
                            @if ($truck->documents && $truck->documents->count())
                                <div style="margin-top:14px">
                                    <div class="doc-chips">
                                        @foreach ($truck->documents as $doc)
                                            <a href="{{ Storage::url($doc->path) }}" target="_blank" class="doc-chip">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                                    <path d="M14 2v6h6" />
                                                </svg>
                                                {{ $doc->name }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>{{-- /compliance --}}

                {{-- ── Pane: Maintenance ── --}}
                <div class="detail-pane" data-pane="maintenance">
                    <div class="card">
                        <div class="card__head">
                            <div class="card__title">
                                <h3>Service History</h3>
                            </div>
                            <button class="btn btn--sm btn--primary">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path d="M12 5v14M5 12h14" />
                                </svg>
                                Log Service
                            </button>
                        </div>
                        <div class="card__body">
                            @forelse($maintenanceHistory ?? [] as $log)
                                <div class="info-row">
                                    <span class="info-row__key">
                                        {{ \Carbon\Carbon::parse($log->date)->format('Y-m-d') }}
                                        — {{ $log->description }}
                                    </span>
                                    <span class="info-row__val">€ {{ number_format($log->cost, 0, '.', ',') }}</span>
                                </div>
                            @empty
                                <p class="muted" style="padding:8px 0">No service records yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>{{-- /maintenance --}}

            </div>{{-- /LEFT --}}

            {{-- ── Sidebar ─────────────────────────────────── --}}
            <aside class="detail-side">

                {{-- QR Card --}}
                <div class="card side-qr">
                    <div class="card__title" style="justify-content:center;margin-bottom:10px">
                        <h3>QR Code</h3>
                    </div>
                    <div class="side-qr__frame">
                        <div id="sideQr"></div>
                    </div>
                    {{-- <div class="side-qr__code">flecso://truck/{{ $truck->id }}</div> --}}
                    <div class="side-qr__actions">
                        <button class="btn btn--sm btn--ghost"
                            onclick="showQR('Truck {{ $truck->plate }}', '{{ $truck->id }}')">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                            View
                        </button>
                        <button class="btn btn--sm btn--ghost" id="sideQrDl">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3" />
                            </svg>
                            Save
                        </button>
                    </div>
                </div>

                {{-- Assigned Driver --}}
                {{-- @if ($driver)
        <div class="card">
          <div class="card__head"><div class="card__title"><h3>Assigned Driver</h3></div></div>
          <div class="card__body">
            <div class="assignee" style="background:transparent;padding:0">
              <img src="{{ $driver->avatar }}" alt="{{ $driver->name }}">
              <div style="flex:1">
                <div class="assignee__name">{{ $driver->name }}</div>
                <div class="assignee__sub">Primary driver · {{ $driver->rating }} ★</div>
              </div>
              <a href="{{ route('drivers.show', $driver->id) }}" class="btn btn--sm btn--ghost">View</a>
            </div>
          </div>
        </div>
      @endif --}}

                {{-- Actions --}}
                <div class="card">
                    <div class="card__head">
                        <div class="card__title">
                            <h3>Actions</h3>
                        </div>
                    </div>
                    <div class="card__body side-actions">
                        <button class="side-action">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M12 2v4M5 7l2.5 2.5M19 7l-2.5 2.5M12 22a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
                                <path d="m8 13 4-4 4 4-4 4z" />
                            </svg>
                            Assign to a trip
                        </button>
                        <button class="side-action">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" />
                                <path d="M16 2v4M8 2v4M3 10h18" />
                            </svg>
                            Schedule service
                        </button>
                        <button class="side-action">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
                            </svg>
                            Generate report
                        </button>
                        <button class="side-action">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" />
                            </svg>
                            Upload documents
                        </button>
                        {{-- <form method="POST" action="{{ route('trucks.archive', $truck->id) }}"
                onsubmit="return confirm('Archive this truck?')">
            @csrf @method('PATCH')
            <button type="submit" class="side-action danger" style="width:100%">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5" rx="1"/>
                <line x1="10" y1="12" x2="14" y2="12"/>
              </svg>
              Archive truck
            </button>
          </form> --}}
                    </div>
                </div>

            </aside>
        </div>{{-- /detail-grid --}}

    </section>

    <script src="{{ asset('js/trucks.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /* ── Tabs ──────────────────────────────────────── */
            document.querySelectorAll('.detail-tabs button').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.detail-tabs button').forEach(function(b) {
                        b.classList.remove('active');
                    });
                    btn.classList.add('active');
                    document.querySelectorAll('.detail-pane').forEach(function(p) {
                        p.classList.toggle('active', p.dataset.pane === btn.dataset.pane);
                    });
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {

            const QR_TEXT = '{{ $truck->id }}';

            /* ── Sidebar QR (small) ── */
            if (document.getElementById('sideQr')) {
                new QRCode(document.getElementById('sideQr'), {
                    text: QR_TEXT,
                    width: 140,
                    height: 140,
                });
            }

            /* ── Modal QR (lazy load) ── */
            let modalQrGenerated = false;

            const qrModal = document.getElementById('qrModal');

            qrModal.addEventListener('shown.bs.modal', function() {

                if (!modalQrGenerated) {
                    new QRCode(document.getElementById('qrCanvas'), {
                        text: QR_TEXT,
                        width: 240,
                        height: 240,
                    });

                    modalQrGenerated = true;
                }
            });

            /* ── Download QR ── */
            document.getElementById('downloadQR')?.addEventListener('click', function() {
                const canvas = document.querySelector('#qrCanvas canvas');
                if (!canvas) return;

                const link = document.createElement('a');
                link.download = '{{ $truck->id }}-qr.png';
                link.href = canvas.toDataURL();
                link.click();
            });

        });
    </script>
@endsection
