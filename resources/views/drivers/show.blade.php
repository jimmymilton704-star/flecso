@extends('layouts.app')

@section('title', $driver->full_name . ' · Driver — Flecso')


<link rel="stylesheet" href="{{ asset('css/drivers.css') }}" />
<link rel="stylesheet" href="{{ asset('css/detail.css') }}" />
<link href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" rel="stylesheet">


@section('content')

    <section class="page" id="detailRoot">

        {{-- Back Navigation --}}
        <a href="{{ route('drivers.index') }}" class="detail-back">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M19 12H5M12 19l-7-7 7-7" />
            </svg>
            Back to Drivers
        </a>

        {{-- ── Hero Section ─────────────────────────────────── --}}
        <div class="detail-hero">
            <img class="detail-hero__img detail-hero__img--circle"
                src="{{ $driver->driver_photo ? asset($driver->driver_photo) : asset('images/default-avatar.png') }}"
                alt="{{ $driver->full_name }}" />

            <div class="detail-hero__body">
                <div class="detail-hero__meta">
                    <span class="detail-hero__id">DRV-{{ str_pad($driver->id, 4, '0', STR_PAD_LEFT) }}</span>
                    <span class="badge badge--{{ $driver->status === 'active' ? 'success' : 'danger' }}">
                        {{ ucfirst($driver->status) }}
                    </span>
                    <span class="badge badge--neutral">⭐ 4.9</span>
                </div>

                <h1>{{ $driver->full_name }}</h1>

                <div class="detail-hero__sub">
                    <span><i class="icon-mail"></i> {{ $driver->email }}</span>
                    <span><i class="icon-phone"></i> {{ $driver->phone }}</span>
                    <span><i class="icon-card"></i> License {{ $driver->license_number }}</span>
                </div>
            </div>

            <div class="detail-hero__actions">
                <button class="btn btn--ghost">Message</button>
                <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn--ghost"><svg viewBox="0 0 24 24"
                        width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                        <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z"></path>
                    </svg>Edit</a>
                <a  class="btn btn--primary"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="6" cy="19" r="3"></circle>
                        <circle cx="18" cy="5" r="3"></circle>
                        <path d="M6 16V8a4 4 0 0 1 4-4h4M18 8v8a4 4 0 0 1-4 4h-4"></path>
                    </svg>Assign Trip</a>
            </div>
        </div>

        {{-- ── Quick Stats ─────────────────────────────────── --}}
        <div class="detail-quickstats">
            <div class="qs">
                <div class="qs__label">Trips Completed</div>
                <div class="qs__value">312</div>
                <div class="qs__sub">Lifetime</div>
            </div>
            <div class="qs">
                <div class="qs__label">On-Time Rate</div>
                <div class="qs__value">96.0%</div>
                <div class="qs__sub">Last 90 days</div>
            </div>
            <div class="qs">
                <div class="qs__label">Hours This Month</div>
                <div class="qs__value">148h</div>
                <div class="qs__sub">of 176h max</div>
            </div>
            <div class="qs">
                <div class="qs__label">License Expiry</div>
                <div class="qs__value {{ \Carbon\Carbon::parse($driver->license_expiry)->isPast() ? 'text-danger' : '' }}">
                    {{ $driver->license_expiry }}
                </div>
                <div class="qs__sub">{{ \Carbon\Carbon::parse($driver->license_expiry)->isPast() ? 'Expired' : 'Valid' }}
                </div>
            </div>
        </div>

        {{-- ── Main Grid ────────────────────────────────────── --}}
        <div class="detail-grid">
            <div class="main-content">
                {{-- Tabs --}}
                <div class="detail-tabs" role="tablist">
                    <button class="active" data-pane="personal">Personal</button>
                    <button data-pane="license">License & Professional</button>
                    <button data-pane="identity">Identity (Italy)</button>
                    <button data-pane="documents">Documents</button>
                </div>

                {{-- Pane: Personal --}}
                <div class="detail-pane active" data-pane="personal">
                    <div class="card">
                        <div class="card__head">
                            <div class="card__title">
                                <h3>Personal Information</h3>
                            </div>
                        </div>
                        <div class="card__body">
                            <div class="info-grid">
                                <div class="info-row"><span class="info-row__key">Full Name</span><span
                                        class="info-row__val">{{ $driver->full_name }}</span></div>
                                <div class="info-row"><span class="info-row__key">Place of Birth</span><span
                                        class="info-row__val">{{ $driver->place_of_birth ?? '—' }}</span></div>
                                <div class="info-row"><span class="info-row__key">Date of Birth</span><span
                                        class="info-row__val">{{ $driver->date_of_birth ?? '—' }}</span></div>
                                <div class="info-row"><span class="info-row__key">Nationality</span><span
                                        class="info-row__val">{{ $driver->nationality ?? '—' }}</span></div>
                                <div class="info-row"><span class="info-row__key">Residential Address</span><span
                                        class="info-row__val">{{ $driver->residential_address ?? '—' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pane: License --}}
                <div class="detail-pane" data-pane="license">
                    <div class="card">
                        <div class="card__head">
                            <div class="card__title">
                                <h3>Professional Licenses</h3>
                            </div>
                        </div>
                        <div class="card__body">
                            <div class="info-grid">
                                <div class="info-row"><span class="info-row__key">License Number</span><span
                                        class="info-row__val">{{ $driver->license_number }}</span></div>
                                <div class="info-row"><span class="info-row__key">Category</span><span
                                        class="info-row__val">{{ $driver->driving_license_category ?? '—' }}</span></div>
                                <div class="info-row"><span class="info-row__key">CQC Number</span><span
                                        class="info-row__val">{{ $driver->cqc_number ?? '—' }}</span></div>
                                <div class="info-row"><span class="info-row__key">CQC Expiry</span><span
                                        class="info-row__val">{{ $driver->cqc_expiry ?? '—' }}</span></div>
                                <div class="info-row"><span class="info-row__key">Tachograph Card</span><span
                                        class="info-row__val">{{ $driver->tachograph_card_number ?? '—' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pane: Identity (Italy) --}}
                <div class="detail-pane" data-pane="identity">
                    <div class="card">
                        <div class="card__head">
                            <div class="card__title">
                                <h3>Identity</h3>
                            </div>
                        </div>
                        <div class="card__body">
                            <div class="info-grid">
                                <div class="info-row"><span class="info-row__key">Fiscal Code</span><span
                                        class="info-row__val"><code>{{ $driver->fiscal_code ?? '—' }}</code></span></div>
                                <div class="info-row"><span class="info-row__key">Work Permit No.</span><span
                                        class="info-row__val">{{ $driver->work_permit_number ?? '—' }}</span></div>
                                <div class="info-row"><span class="info-row__key">Work Permit Expiry</span><span
                                        class="info-row__val">{{ $driver->work_permit_expiry ?? '—' }}</span></div>
                                <div class="info-row"><span class="info-row__key">Medical Fitness Date</span><span
                                        class="info-row__val">{{ $driver->medical_fitness_date ?? '—' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pane: Documents --}}
                <div class="detail-pane" data-pane="documents">
                    <div class="card">
                        <div class="card__head">
                            <div class="card__title">
                                <h3>Documents</h3>
                            </div>
                        </div>

                        <div class="card__body">
                            <div class="document-list">
                                @php
                                    $docs = [
                                        ['label' => 'License Front', 'file' => $driver->license_front],
                                        ['label' => 'License Back', 'file' => $driver->license_back],
                                        ['label' => 'CQC Card', 'file' => $driver->cqc_card],
                                        ['label' => 'Work Permit', 'file' => $driver->work_permit_file],
                                        ['label' => 'Medical Certificate', 'file' => $driver->medical_certificate],
                                    ];
                                @endphp

                                @foreach ($docs as $doc)
                                    @if ($doc['file'])
                                        <a href="{{ asset($doc['file']) }}" class="doc-item ">
                                            <span class="doc-chip"><svg viewBox="0 0 24 24" width="12"
                                                    height="12" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                                    </path>
                                                    <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"></path>
                                                </svg>{{ $doc['label'] }}</span>

                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ── Sidebar ─────────────────────────────────── --}}
            
            <aside class="detail-side">
                @if($truck)
                <div class="card">
                    <div class="card__head">
                        <h3>Assigned Truck</h3>
                    </div>
                    <div class="card__body">
                        <div class="assignee" style="background:transparent;padding:0;margin-bottom:10px">
                            <img class="glightbox" src="{{ asset($truck->image ? $truck->image : 'https://via.placeholder.com/40')  }}" alt="Truck"
                                style="width:52px;height:52px;border-radius:10px;object-fit:cover; cursor:pointer;">
                            <div style="flex:1">
                                <div class="assignee__name">{{ $truck->truck_number }}</div>
                                <div class="assignee__sub">{{ $truck->truck_type_category }}</div>
                            </div>
                        </div>
                        <a href="{{ route('trucks.show', $truck->id) }}" class="btn btn--sm btn--ghost btn--block">View
                            Truck</a>
                    </div>
                </div>
                @endif

                <div class="card">
                    <div class="card__head">
                        <div class="card__title">
                            <h3>Recent Activity</h3>
                        </div>
                    </div>
                    <div class="card__body">
                        <div class="info-row" style="padding-top:0"><span class="info-row__key">Completed
                                TR-20476</span><span class="info-row__val">3h ago</span></div>
                        <div class="info-row"><span class="info-row__key">Started shift</span><span
                                class="info-row__val">Today 06:00</span></div>
                        <div class="info-row"><span class="info-row__key">Safety training</span><span
                                class="info-row__val">2 days ago</span></div>
                    </div>
                </div>
            </aside>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab switching logic
            const tabs = document.querySelectorAll('.detail-tabs button');
            const panes = document.querySelectorAll('.detail-pane');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.pane;
                    tabs.forEach(t => t.classList.remove('active'));
                    panes.forEach(p => p.classList.remove('active'));

                    tab.classList.add('active');
                    document.querySelector(`.detail-pane[data-pane="${target}"]`).classList.add(
                        'active');
                });
            });

            // Initialize Lightbox for documents
            const lightbox = GLightbox({
                selector: '.glightbox'
            });
        });
    </script>
@endsection
