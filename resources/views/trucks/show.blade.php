{{--
  truck-detail.blade.php
  Extends your main layout. Only the <section> content + QR modal.

  Controller should pass:
    $truck   — Truck model (matches the schema provided)
    $driver  — Driver model|null
    $maintenanceHistory — Collection
--}}

@extends('layouts.app')

@section('title', $truck->truck_number . ' · Truck — Flecso')

{{-- @push('styles') --}}
<link rel="stylesheet" href="{{ asset('css/trucks.css') }}" />
<link rel="stylesheet" href="{{ asset('css/detail.css') }}" />
{{-- @endpush --}}

@section('content')

    @php
        /*
           Fields 'mileage' and 'last_service' are not in the database schema provided.
           Commenting out calculations to avoid errors.
        */
        // $mileageRaw = (int) preg_replace('/\D/', '', $truck->mileage);
        // $serviceDoneKm = $mileageRaw - 8000;
        // $serviceDueKm = $mileageRaw + 12000;
    @endphp

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
            <img class="detail-hero__img" src="{{ asset($truck->image) }}" alt="{{ $truck->truck_number }}" />

            <div class="detail-hero__body">
                <div class="detail-hero__meta">
                    <span class="detail-hero__id">Truck ID</span>

                    @switch($truck->status)
                        @case('active')
                        @case('available')
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

                    <span class="badge badge--neutral">{{ $truck->truck_type_category }}</span>
                    <span class="badge badge--orange">{{ $truck->fuel_type }}</span>
                </div>

                <h1>{{ $truck->truck_number }}</h1>

                <div class="detail-hero__sub">
                    <span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
                        </svg>
                        Plate {{ $truck->license_plate_number }}
                    </span>
                    <span>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2" />
                        </svg>
                        {{ $truck->capacity_tons }} tons capacity
                    </span>
                </div>
            </div>

            <div class="detail-hero__actions">
                <button class="btn btn--ghost" onclick="showQR('Truck {{ $truck->license_plate_number }}', '{{ $truck->id }}')">
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
                <div class="qs__value">{{ $truck->capacity_tons }} t</div>
                <div class="qs__sub">Max payload {{ number_format($truck->payload_capacity_kg) }} kg</div>
            </div>

            {{-- Mileage and Service stats commented out as they are missing from schema --}}
            {{-- 
            <div class="qs">
                <div class="qs__label">... Mileage</div>
                <div class="qs__value">{{ $truck->mileage }}</div>
            </div> 
            --}}

            <div class="qs">
                <div class="qs__label">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" />
                    </svg>
                    Next Inspection
                </div>
                <div class="qs__value" style="font-size:17px">
                    {{ $truck->next_inspection_date ? \Carbon\Carbon::parse($truck->next_inspection_date)->format('Y-m-d') : 'Not Set' }}
                </div>
                <div class="qs__sub">Annual Review</div>
            </div>
        </div>

        {{-- ── Main Grid ────────────────────────────────────── --}}
        <div class="detail-grid">
            <div>
                {{-- Tabs --}}
                <div class="detail-tabs" role="tablist">
                    <button class="active" data-pane="overview">Overview</button>
                    <button data-pane="specs">Technical Specs</button>
                    <button data-pane="compliance">Compliance</button>

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
                                    <span class="info-row__val">{{ $truck->truck_number }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">License Plate</span>
                                    <span class="info-row__val"><code>{{ $truck->license_plate_number }}</code></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Category</span>
                                    <span class="info-row__val">{{ $truck->truck_type_category }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Capacity</span>
                                    <span class="info-row__val">{{ $truck->capacity_tons }} tons</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Fuel Type</span>
                                    <span class="info-row__val">{{ $truck->fuel_type }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Status</span>
                                    <span class="info-row__val">
                                        <span class="badge badge--neutral">{{ ucfirst($truck->status) }}</span>
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
                                    <span class="info-row__val"><code>{{ $truck->vin_number ?? '—' }}</code></span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Registration Date</span>
                                    <span class="info-row__val">
                                        {{ $truck->first_registration_date ? \Carbon\Carbon::parse($truck->first_registration_date)->format('Y-m-d') : '—' }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Usage Type</span>
                                    <span class="info-row__val">{{ $truck->usage_type ?? 'Owned' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
                                        class="info-row__val">{{ $truck->gvw_kg ? number_format($truck->gvw_kg) . ' kg' : '—' }}</span>
                                </div>
                                <div class="info-row"><span class="info-row__key">Payload Capacity</span><span
                                        class="info-row__val">{{ $truck->payload_capacity_kg ? number_format($truck->payload_capacity_kg) . ' kg' : '—' }}</span>
                                </div>
                                <div class="info-row"><span class="info-row__key">Number of Axles</span><span
                                        class="info-row__val">{{ $truck->number_of_axles ?? '—' }}</span></div>
                                <div class="info-row"><span class="info-row__key">Engine Class</span><span
                                        class="info-row__val">{{ $truck->engine_class ?? '—' }}</span></div>
                                <div class="info-row"><span class="info-row__key">Fuel Type</span><span
                                        class="info-row__val">{{ $truck->fuel_type }}</span></div>
                                {{-- Transmission and Engine Power not in schema --}}
                                {{-- <div class="info-row"><span class="info-row__key">Transmission</span>...</div> --}}
                            </div>
                        </div>
                    </div>
                </div>

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
                                    <span class="info-row__key">Next Inspection</span>
                                    <span class="info-row__val">
                                        {{ $truck->next_inspection_date ? \Carbon\Carbon::parse($truck->next_inspection_date)->format('Y-m-d') : '—' }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Insurance Policy</span>
                                    <span class="info-row__val">{{ $truck->insurance_policy_number ?? '—' }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Insurance Expiry</span>
                                    <span class="info-row__val">
                                        {{ $truck->insurance_expiry_date ? \Carbon\Carbon::parse($truck->insurance_expiry_date)->format('Y-m-d') : '—' }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Tachograph Expiry</span>
                                    <span class="info-row__val">
                                        {{ $truck->tachograph_calibration_expiry ? \Carbon\Carbon::parse($truck->tachograph_calibration_expiry)->format('Y-m-d') : '—' }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="info-row__key">Road Tax (Bollo) Expiry</span>
                                    <span class="info-row__val">
                                        {{ $truck->bollo_expiry_date ? \Carbon\Carbon::parse($truck->bollo_expiry_date)->format('Y-m-d') : '—' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

            {{-- ── Sidebar ─────────────────────────────────── --}}
            <aside class="detail-side">
                <div class="card side-qr">
                    <div class="card__title" style="justify-content:center;margin-bottom:10px">
                        <h3>QR Code</h3>
                    </div>
                    <div class="side-qr__frame">
                        <div id="sideQr"></div>
                    </div>
                    <div class="side-qr__actions">
                        <button class="btn btn--sm btn--ghost"
                            onclick="showQR('Truck {{ $truck->license_plate_number }}', '{{ $truck->id }}')">
                            View
                        </button>
                        <button class="btn btn--sm btn--ghost" id="sideQrDl">Save</button>
                    </div>
                </div>
                <div class="card">
                    <div class="card__head">
                        <div class="card__title">
                            <h3>Documents</h3>
                        </div>
                    </div>

                    <div class="card__body side-actions">

                        @php
                            $hasDoc = filled($truck->documento_unico);
                            $hasImage = filled($truck->image);
                            $url = asset($truck->documento_unico);
                            $ext = strtolower(pathinfo($truck->documento_unico, PATHINFO_EXTENSION));
                        @endphp

                        {{-- IMAGE --}}
                        @if ($hasImage)
                            <a href="{{ asset($truck->image) }}" class="side-action glightbox"
                                data-gallery="truck-gallery" data-type="image">
                                <svg fill="#2d193e" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16"
                                    viewBox="-9.83 -9.83 117.99 117.99" xml:space="preserve" stroke="#2d193e"
                                    stroke-width="0.00098328" transform="matrix(1, 0, 0, 1, 0, 0)rotate(0)">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"
                                        stroke="#CCCCCC" stroke-width="0.393312"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <g>
                                            <g>
                                                <path
                                                    d="M73.229,45.588c-4.565,2.174-8.287,8.774-12.641,4.419c-2.646-2.872-5.785-7.441-9.392-12.609 c-3.9-5.556-8.872-10.333-14.722-4.875c-6.531,6.143-11.017,22.813-11.406,31.392l61.711,0.021 C85.367,58.895,79.882,42.42,73.229,45.588z">
                                                </path>
                                                <circle cx="72.941" cy="28.32" r="7.588"></circle>
                                                <path
                                                    d="M96.072,11.002H15.777c-1.245,0-2.256,1.01-2.256,2.255v11.406H2.256C1.011,24.664,0,25.673,0,26.918v58.152 c0,1.246,1.011,2.255,2.256,2.255h80.295c1.245,0,2.256-1.011,2.256-2.255V73.666h11.266c1.244,0,2.256-1.01,2.256-2.255V13.257 C98.328,12.013,97.316,11.002,96.072,11.002z M78.752,81.272H6.055V30.719h7.466v35.148c-1.133,4.377-1.831,8.583-1.973,11.712 l61.712,0.02c-0.271-0.964-0.693-2.35-1.242-3.933h6.734V81.272z M92.273,67.61H19.576V17.058h72.697V67.61z">
                                                </path>
                                            </g>
                                        </g>
                                    </g>
                                </svg> View Image
                            </a>
                        @else
                            {{-- image not available --}}
                        @endif

                        {{-- DOCUMENT --}}
                        @if ($hasDoc)
                            @php
                                $ext = strtolower(pathinfo($truck->documento_unico, PATHINFO_EXTENSION));
                            @endphp

                            @if ($ext === 'pdf')
                                <a href="{{ $url }}" target="_blank" class="side-action">
                                    <svg version="1.1" id="_x34_" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" width="16" height="16"
                                        viewBox="0 0 512 512" xml:space="preserve" fill="#000000">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <g>
                                                <polygon style="fill:#FFFEFE;"
                                                    points="475.435,117.825 475.435,512 47.791,512 47.791,0.002 357.613,0.002 412.491,54.881 ">
                                                </polygon>
                                                <rect x="36.565" y="34.295" style="fill:#B43331;" width="205.097"
                                                    height="91.768"></rect>
                                                <g>
                                                    <g>
                                                        <path style="fill:#FFFEFE;"
                                                            d="M110.133,64.379c-0.905-2.186-2.111-4.146-3.769-5.804c-1.659-1.658-3.694-3.015-6.031-3.92 c-2.412-0.98-5.127-1.432-8.141-1.432H69.652v58.195h11.383V89.481h11.157c3.015,0,5.729-0.452,8.141-1.432 c2.337-0.905,4.371-2.261,6.031-3.92c1.658-1.658,2.865-3.543,3.769-5.804c0.828-2.186,1.281-4.523,1.281-6.935 C111.413,68.902,110.961,66.565,110.133,64.379z M97.845,77.118c-1.508,1.432-3.618,2.186-6.182,2.186H81.035V63.323h10.628 c2.564,0,4.674,0.754,6.182,2.261c1.432,1.432,2.185,3.392,2.185,5.804C100.031,73.726,99.277,75.686,97.845,77.118z">
                                                        </path>
                                                        <path style="fill:#FFFEFE;"
                                                            d="M164.558,75.761c-0.075-2.035-0.15-3.844-0.377-5.503c-0.225-1.659-0.603-3.166-1.131-4.598 c-0.527-1.357-1.206-2.714-2.111-3.92c-2.035-2.94-4.522-5.126-7.312-6.483c-2.864-1.357-6.256-2.035-10.252-2.035H122.42v58.195 h20.956c3.996,0,7.388-0.678,10.252-2.035c2.79-1.357,5.277-3.543,7.312-6.483c0.905-1.206,1.584-2.563,2.111-3.92 c0.528-1.432,0.905-2.94,1.131-4.598c0.227-1.658,0.301-3.468,0.377-5.503c0.075-1.96,0.075-4.146,0.075-6.558 C164.633,79.908,164.633,77.721,164.558,75.761z M153.175,88.2c0,1.734-0.15,3.091-0.302,4.297 c-0.151,1.131-0.376,2.186-0.678,2.94c-0.301,0.829-0.754,1.583-1.281,2.261c-1.885,2.412-4.749,3.543-8.518,3.543h-8.668V63.323 h8.668c3.769,0,6.634,1.206,8.518,3.618c0.528,0.678,0.98,1.357,1.281,2.186c0.302,0.829,0.528,1.809,0.678,3.015 c0.152,1.131,0.302,2.563,0.302,4.221c0.075,1.659,0.075,3.694,0.075,5.955C153.251,84.581,153.251,86.541,153.175,88.2z">
                                                        </path>
                                                        <path style="fill:#FFFEFE;"
                                                            d="M213.18,63.323V53.222h-38.37v58.195h11.383V87.823h22.992V77.646h-22.992V63.323H213.18z">
                                                        </path>
                                                    </g>
                                                    <g>
                                                        <path style="fill:#FFFEFE;"
                                                            d="M110.133,64.379c-0.905-2.186-2.111-4.146-3.769-5.804c-1.659-1.658-3.694-3.015-6.031-3.92 c-2.412-0.98-5.127-1.432-8.141-1.432H69.652v58.195h11.383V89.481h11.157c3.015,0,5.729-0.452,8.141-1.432 c2.337-0.905,4.371-2.261,6.031-3.92c1.658-1.658,2.865-3.543,3.769-5.804c0.828-2.186,1.281-4.523,1.281-6.935 C111.413,68.902,110.961,66.565,110.133,64.379z M97.845,77.118c-1.508,1.432-3.618,2.186-6.182,2.186H81.035V63.323h10.628 c2.564,0,4.674,0.754,6.182,2.261c1.432,1.432,2.185,3.392,2.185,5.804C100.031,73.726,99.277,75.686,97.845,77.118z">
                                                        </path>
                                                    </g>
                                                </g>
                                                <polygon style="opacity:0.08;fill:#040000;"
                                                    points="475.435,117.825 475.435,512 47.791,512 47.791,419.581 247.706,219.667 259.541,207.832 266.099,201.273 277.029,190.343 289.995,177.377 412.491,54.881 ">
                                                </polygon>
                                                <polygon style="fill:#BBBBBA;"
                                                    points="475.435,117.836 357.6,117.836 357.6,0 "></polygon>
                                                <g>
                                                    <path style="fill:#B43331;"
                                                        d="M414.376,370.658c-2.488-4.372-5.88-8.518-10.101-12.287c-3.467-3.166-7.538-6.106-12.137-8.82 c-18.543-10.93-45.003-16.207-80.961-16.207h-3.618c-1.96-1.809-3.996-3.618-6.106-5.503 c-13.644-12.287-24.499-25.63-32.942-40.48c16.583-36.561,24.499-69.126,23.519-96.867c-0.151-4.674-0.83-9.046-2.036-13.117 c-1.808-6.558-4.824-12.363-9.046-17.112c-0.075-0.075-0.075-0.075-0.15-0.151c-6.709-7.538-16.056-11.835-25.555-11.835 c-9.574,0-18.393,4.146-24.802,11.76c-6.331,7.538-9.724,17.866-9.875,30.002c-0.225,18.544,1.282,36.108,4.448,52.315 c0.301,1.282,0.528,2.563,0.829,3.844c3.166,14.7,7.84,28.645,13.871,41.611c-7.086,14.398-14.248,26.836-19.222,35.279 c-3.769,6.408-7.916,13.117-12.213,19.826c-19.373,3.468-35.807,7.689-50.129,12.966c-19.374,7.011-34.903,16.056-46.059,26.836 c-7.238,6.935-12.137,14.323-14.55,22.012c-2.563,7.915-2.411,15.83,0.453,22.916c2.638,6.558,7.387,12.061,13.719,15.83 c1.508,0.905,3.091,1.658,4.749,2.337c4.825,1.96,10.102,3.015,15.604,3.015c12.74,0,25.856-5.503,36.938-15.378 c20.654-18.469,41.988-48.169,54.576-66.94c10.327-1.583,21.559-2.94,34.224-4.297c14.927-1.508,28.118-2.412,40.104-2.865 c3.694,3.317,7.237,6.483,10.63,9.498c18.846,16.81,33.168,28.947,46.134,37.465c0,0.075,0.075,0.075,0.15,0.075 c5.127,3.392,10.026,6.181,14.926,8.443c5.502,2.563,11.081,3.92,16.81,3.92c7.237,0,14.021-2.186,19.675-6.181 c5.729-4.146,9.875-10.101,11.76-16.81C420.181,387.694,418.899,378.724,414.376,370.658z M247.706,219.667 c-1.056-9.348-1.508-19.072-1.357-29.324c0.15-9.724,3.694-16.283,8.895-16.283c3.919,0,8.066,3.543,9.951,10.327 c0.528,2.035,0.905,4.372,0.98,7.01c0.15,3.166,0.075,6.483-0.075,9.875c-0.452,9.574-2.112,19.75-4.976,30.681 c-1.734,7.011-3.995,14.323-6.784,21.936C251.173,243.186,248.911,231.803,247.706,219.667z M121.968,418.073 c-1.282-3.166,0.15-9.272,7.99-16.81c11.985-11.458,30.755-20.504,56.914-27.364c-4.976,6.784-9.875,12.966-14.625,18.619 c-7.237,8.744-14.172,16.132-20.429,21.71c-5.351,4.824-11.232,7.84-16.81,8.594c-0.98,0.151-1.96,0.226-2.94,0.226 C127.169,423.049,123.173,421.089,121.968,418.073z M242.428,337.942l0.528-0.829l-0.829,0.151 c0.15-0.377,0.377-0.754,0.602-1.055c3.167-5.352,7.161-12.212,11.458-20.127l0.377,0.829l0.98-2.035 c3.166,4.523,6.634,8.971,10.252,13.267c1.735,2.035,3.544,3.995,5.352,5.955l-1.205,0.075l1.055,0.98 c-3.09,0.226-6.331,0.528-9.573,0.829c-2.036,0.226-4.147,0.377-6.257,0.603C250.796,337.037,246.499,337.49,242.428,337.942z M369.298,384.98c-8.97-5.729-18.997-13.795-31.36-24.575c17.564,1.809,31.36,5.654,41.159,11.383 c4.297,2.488,7.538,5.051,9.724,7.538c3.619,3.844,4.901,7.312,4.221,9.649c-0.602,2.337-3.241,3.92-6.483,3.92 c-1.885,0-3.844-0.452-5.879-1.432c-3.468-1.658-7.086-3.694-10.931-6.181C369.598,385.282,369.448,385.131,369.298,384.98z">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                    </svg> View PDF
                                </a>

                                {{-- DOC / DOCX (force preview in browser) --}}
                            @elseif (in_array($ext, ['doc', 'docx']))
                                <a href="https://docs.google.com/gview?url={{ urlencode($url) }}&embedded=true"
                                    target="_blank" class="side-action">
                                    📄 View Document
                                </a>

                                {{-- Other files --}}
                            @else
                                <a href="{{ $url }}" target="_blank" class="side-action">
                                    📄 Open File
                                </a>
                            @endif
                        @else
                            {{-- documento_unico not available --}}
                        @endif

                        @if (!$hasDoc && !$hasImage)
                            <p class="muted">No documents available</p>
                        @endif

                    </div>
                </div>
            </aside>
        </div>

    </section>

    <link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script src="{{ asset('js/trucks.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            /* Tabs Logic */
            document.querySelectorAll('.detail-tabs button').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.detail-tabs button').forEach(b => b.classList
                        .remove('active'));
                    btn.classList.add('active');
                    document.querySelectorAll('.detail-pane').forEach(p => p.classList.toggle(
                        'active', p.dataset.pane === btn.dataset.pane));
                });
            });

            /* QR Generation */
            const QR_TEXT = '{{ $truck->truck_number }}';
            if (document.getElementById('sideQr')) {
                new QRCode(document.getElementById('sideQr'), {
                    text: QR_TEXT,
                    width: 140,
                    height: 140,
                });
            }
        });
    </script>
    <script>
        const lightbox = GLightbox({
            selector: '.glightbox',
            touchNavigation: true,
            loop: false
        });
    </script>
@endsection
