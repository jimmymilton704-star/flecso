@extends('layouts.app')

@section('title', 'Container Details')
@section('body-class', 'page-dashboard')

@section('content')

<section class="page" id="detailRoot">

    {{-- Back --}}
    <a href="{{ route('containers.index') }}" class="detail-back">
        ← Back to Containers
    </a>

    {{-- HERO --}}
    <div class="detail-hero">

        <img class="detail-hero__img"
            src="{{ $container->image ?? 'https://via.placeholder.com/200' }}" alt="">

        <div class="detail-hero__body">

            <div class="detail-hero__meta">
                <span class="detail-hero__id">Container ID</span>

                {{-- STATUS --}}
                <span class="badge 
                    @if($container->status=='available') badge--info
                    @elseif($container->status=='in_transit') badge--success
                    @elseif($container->status=='loading') badge--primary
                    @elseif($container->status=='maintenance') badge--warn
                    @endif">
                    <span class="badge-dot"></span>
                    {{ ucfirst(str_replace('_',' ',$container->status)) }}
                </span>

                <span class="badge badge--neutral">
                    {{ $container->type }}
                </span>

                <span class="badge badge--orange">
                    ISO {{ $container->iso_code }}
                </span>
            </div>

            <h1>{{ $container->container_number }}</h1>

            <div class="detail-hero__sub">
                <span> {{ $container->owner_code }} · {{ $container->serial_number }}</span>
                <span> {{ $container->location ?? 'N/A' }}</span>
                <span> {{ $container->weight }} tons</span>
            </div>
        </div>

        <div class="detail-hero__actions">

            <button class="btn btn--ghost"
                onclick="showQR('{{ $container->container_number }}','ISO {{ $container->iso_code }}')">
                QR Code
            </button>

            <a href="{{ route('containers.edit',$container->id) }}" class="btn btn--ghost">
                Edit
            </a>

            <button class="btn btn--primary">
                Assign to Trip
            </button>
        </div>
    </div>

    {{-- QUICK STATS --}}
    <div class="detail-quickstats">

        <div class="qs">
            <div class="qs__label">Current Weight</div>
            <div class="qs__value">{{ $container->weight }} t</div>
            <div class="qs__sub">of max {{ $container->max_weight ?? 'N/A' }} t</div>
        </div>

        <div class="qs">
            <div class="qs__label">Type</div>
            <div class="qs__value">{{ $container->type }}</div>
            <div class="qs__sub">{{ $container->iso_code }}</div>
        </div>

        <div class="qs">
            <div class="qs__label">Location</div>
            <div class="qs__value">{{ $container->location }}</div>
            <div class="qs__sub">Last updated {{ $container->updated_at->diffForHumans() }}</div>
        </div>

        <div class="qs">
            <div class="qs__label">Last Inspection</div>
            <div class="qs__value">{{ $container->last_inspection ?? '-' }}</div>
            <div class="qs__sub">
                Next {{ $container->next_inspection ?? '-' }}
            </div>
        </div>
    </div>

    {{-- DETAILS --}}
    <div class="detail-grid">

        <div>

            {{-- TABS --}}
            <div class="detail-tabs">
                <button class="active" data-pane="iso">ISO 6346</button>
                <button data-pane="csc">CSC Plate</button>
                <button data-pane="logistics">Logistics</button>
            </div>

            {{-- ISO --}}
            <div class="detail-pane active" data-pane="iso">
                <div class="card">
                    <div class="card__body">
                        <div class="info-grid">

                            <div class="info-row">
                                <span class="info-row__key">Container Number</span>
                                <span class="info-row__val">
                                    <code>{{ $container->container_number_full }}</code>
                                </span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Owner Code</span>
                                <span class="info-row__val">{{ $container->owner_code }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Serial Number</span>
                                <span class="info-row__val">{{ $container->serial_number }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">ISO Code</span>
                                <span class="info-row__val">
                                    <code>{{ $container->iso_code }}</code>
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- CSC --}}
            <div class="detail-pane" data-pane="csc">
                <div class="card">
                    <div class="card__body">
                        <div class="info-grid">

                            <div class="info-row">
                                <span>Manufacturer</span>
                                <span>{{ $container->manufacturer ?? '-' }}</span>
                            </div>

                            <div class="info-row">
                                <span>Manufacture Date</span>
                                <span>{{ $container->manufacture_date ?? '-' }}</span>
                            </div>

                            <div class="info-row">
                                <span>Max Weight</span>
                                <span>{{ $container->max_weight ?? '-' }} kg</span>
                            </div>

                            <div class="info-row">
                                <span>Tare Weight</span>
                                <span>{{ $container->tare_weight ?? '-' }} kg</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- LOGISTICS --}}
            <div class="detail-pane" data-pane="logistics">
                <div class="card">
                    <div class="card__body">
                        <div class="info-grid">

                            <div class="info-row">
                                <span>Current Location</span>
                                <span>{{ $container->location }}</span>
                            </div>

                            <div class="info-row">
                                <span>Trip</span>
                                <span>
                                    {{ $container->trip->trip_number ?? 'Not assigned' }}
                                </span>
                            </div>

                            <div class="info-row">
                                <span>Status</span>
                                <span>{{ ucfirst($container->status) }}</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- SIDE --}}
        <aside class="detail-side">

            {{-- QR --}}
            <div class="card side-qr">

                <h3 style="text-align:center">QR Code</h3>

                <div class="side-qr__frame">
                    <canvas id="sideQrCanvas"></canvas>
                </div>

                <div class="side-qr__code">
                    container://{{ $container->container_number }}
                </div>

                <div class="side-qr__actions">
                    <button class="btn btn--sm btn--ghost"
                        onclick="showQR('{{ $container->container_number }}','ISO {{ $container->iso_code }}')">
                        View
                    </button>

                    <button class="btn btn--sm btn--ghost" onclick="downloadSideQR()">
                        Save
                    </button>
                </div>
            </div>

        </aside>
    </div>

</section>

{{-- QR SCRIPT --}}
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('sideQrCanvas');

        QRCode.toCanvas(canvas,
            "container://{{ $container->container_number }}", {
                width: 160
            }
        );
    });

    function downloadSideQR() {
        const canvas = document.getElementById('sideQrCanvas');
        const link = document.createElement('a');
        link.download = "{{ $container->container_number }}.png";
        link.href = canvas.toDataURL();
        link.click();
    }
</script>

@endsection