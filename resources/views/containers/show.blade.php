@extends('layouts.app')

@section('title', $container->container_id . ' · Container — Flecso')

<link rel="stylesheet" href="{{ asset('css/containers.css') }}" />
<link rel="stylesheet" href="{{ asset('css/detail.css') }}" />

@section('content')
<section class="page" id="detailRoot">

    <a href="{{ route('containers.index') }}" class="detail-back">
        ← Back to Containers
    </a>

    <div class="detail-hero">
        <img class="detail-hero__img"
            src="{{ filled($container->image) ? asset($container->image) : 'https://via.placeholder.com/200' }}"
            alt="{{ $container->container_id }}">

        <div class="detail-hero__body">
            <div class="detail-hero__meta">
                <span class="detail-hero__id">Container ID</span>

                <span class="badge
                    @if($container->status=='available') badge--info
                    @elseif($container->status=='in_transit') badge--success
                    @elseif($container->status=='loading') badge--primary
                    @elseif($container->status=='maintenance') badge--warn
                    @else badge--neutral
                    @endif">
                    <span class="badge-dot"></span>
                    {{ ucfirst(str_replace('_',' ', $container->status)) }}
                </span>

                <span class="badge badge--neutral">
                    {{ $container->container_type ?? '-' }}
                </span>

                <span class="badge badge--orange">
                    {{ $container->category_identifier ?? '-' }}
                </span>
            </div>

            <h1>{{ $container->container_id }}</h1>

            <div class="detail-hero__sub">
                <span>{{ $container->owner_code }} · {{ $container->serial_number }}</span>
                <span>{{ $container->weight_capacity ?? 'N/A' }} tons</span>
                <span>{{ $container->container_status ?? 'empty' }}</span>
            </div>
        </div>

        <div class="detail-hero__actions">
            <button class="btn btn--ghost"
                onclick="showQR('{{ $container->container_id }}','{{ $container->container_license_number }}')">
                QR Code
            </button>

            <a href="{{ route('containers.edit', $container->id) }}" class="btn btn--ghost">
                Edit
            </a>

            <button class="btn btn--primary">
                Assign to Trip
            </button>
        </div>
    </div>

    <div class="detail-quickstats">
        <div class="qs">
            <div class="qs__label">Weight Capacity</div>
            <div class="qs__value">{{ $container->weight_capacity ?? '-' }} t</div>
            <div class="qs__sub">Max operating weight {{ $container->max_operating_weight ?? '-' }} kg</div>
        </div>

        <div class="qs">
            <div class="qs__label">Type</div>
            <div class="qs__value">{{ $container->container_type ?? '-' }}</div>
            <div class="qs__sub">{{ $container->iso_type_size_code ?? '-' }}</div>
        </div>

        <div class="qs">
            <div class="qs__label">Status</div>
            <div class="qs__value">{{ ucfirst(str_replace('_',' ', $container->status)) }}</div>
            <div class="qs__sub">Container {{ $container->container_status ?? 'empty' }}</div>
        </div>

        <div class="qs">
            <div class="qs__label">Last Updated</div>
            <div class="qs__value">{{ optional($container->updated_at)->format('Y-m-d') ?? '-' }}</div>
            <div class="qs__sub">{{ optional($container->updated_at)->diffForHumans() ?? '' }}</div>
        </div>
    </div>

    <div class="detail-grid">
        <div>
            <div class="detail-tabs">
                <button class="active" data-pane="iso">ISO 6346</button>
                <button data-pane="csc">CSC Plate</button>
                <button data-pane="logistics">Logistics</button>
            </div>

            <div class="detail-pane active" data-pane="iso">
                <div class="card">
                    <div class="card__body">
                        <div class="info-grid">
                            <div class="info-row">
                                <span class="info-row__key">Container ID</span>
                                <span class="info-row__val"><code>{{ $container->container_id }}</code></span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Container License Number</span>
                                <span class="info-row__val">{{ $container->container_license_number }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Owner Code</span>
                                <span class="info-row__val">{{ $container->owner_code }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Category Identifier</span>
                                <span class="info-row__val">{{ $container->category_identifier }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Serial Number</span>
                                <span class="info-row__val">{{ $container->serial_number }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Check Digit</span>
                                <span class="info-row__val">{{ $container->check_digit }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">ISO Type / Size Code</span>
                                <span class="info-row__val">{{ $container->iso_type_size_code }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-pane" data-pane="csc">
                <div class="card">
                    <div class="card__body">
                        <div class="info-grid">
                            <div class="info-row">
                                <span class="info-row__key">Manufacturer Serial Number</span>
                                <span class="info-row__val">{{ $container->manufacturer_serial_number }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Manufacture Date</span>
                                <span class="info-row__val">{{ $container->manufacture_date ?? '-' }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Max Operating Weight</span>
                                <span class="info-row__val">{{ $container->max_operating_weight ?? '-' }} kg</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Stacking Weight</span>
                                <span class="info-row__val">{{ $container->stacking_weight ?? '-' }} kg</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Next Examination Date</span>
                                <span class="info-row__val">{{ $container->next_examination_date ?? '-' }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Owner Lessor</span>
                                <span class="info-row__val">{{ $container->owner_lessor ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-pane" data-pane="logistics">
                <div class="card">
                    <div class="card__body">
                        <div class="info-grid">
                            <div class="info-row">
                                <span class="info-row__key">Container Type</span>
                                <span class="info-row__val">{{ $container->container_type }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Weight Capacity</span>
                                <span class="info-row__val">{{ $container->weight_capacity }} t</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Status</span>
                                <span class="info-row__val">{{ ucfirst(str_replace('_',' ', $container->status)) }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Container Status</span>
                                <span class="info-row__val">{{ $container->container_status }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">EORI Number</span>
                                <span class="info-row__val">{{ $container->eori_number ?? '-' }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Seal Number</span>
                                <span class="info-row__val">{{ $container->seal_number ?? '-' }}</span>
                            </div>

                            {{-- <div class="info-row">
                                <span class="info-row__key">Admin ID</span>
                                <span class="info-row__val">{{ $container->admin_id }}</span>
                            </div> --}}

                            {{-- <div class="info-row">
                                <span class="info-row__key">Created At</span>
                                <span class="info-row__val">{{ $container->created_at }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-row__key">Updated At</span>
                                <span class="info-row__val">{{ $container->updated_at }}</span>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <aside class="detail-side">
            <div class="card side-qr">
                <h3 style="text-align:center">QR Code</h3>

                <div class="side-qr__frame">
                    <div id="sideQr"></div>
                </div>

                {{-- <div class="side-qr__code">
                    container://{{ $container->container_id }}
                </div> --}}

                <div class="side-qr__actions">
                    <button class="btn btn--sm btn--ghost"
                        onclick="showQR('{{ $container->container_id }}','{{ $container->container_license_number }}')">
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

<script src="{{ asset('js/trucks.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.detail-tabs button').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.querySelectorAll('.detail-tabs button').forEach(function (b) {
                b.classList.remove('active');
            });
            btn.classList.add('active');

            document.querySelectorAll('.detail-pane').forEach(function (p) {
                p.classList.toggle('active', p.dataset.pane === btn.dataset.pane);
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const QR_TEXT = '{{ $container->container_id }}';

    const sideQrEl = document.getElementById('sideQr');
    if (sideQrEl) {
        sideQrEl.innerHTML = '';
        new QRCode(sideQrEl, {
            text: QR_TEXT,
            width: 140,
            height: 140,
        });
    }

    let modalQrGenerated = false;
    const qrModal = document.getElementById('qrModal');

    if (qrModal) {
        qrModal.addEventListener('shown.bs.modal', function () {
            if (!modalQrGenerated) {
                const qrCanvas = document.getElementById('qrCanvas');
                if (qrCanvas) {
                    new QRCode(qrCanvas, {
                        text: QR_TEXT,
                        width: 240,
                        height: 240,
                    });
                    modalQrGenerated = true;
                }
            }
        });
    }

    document.getElementById('downloadQR')?.addEventListener('click', function () {
        const canvas = document.querySelector('#qrCanvas canvas');
        if (!canvas) return;

        const link = document.createElement('a');
        link.download = '{{ $container->container_id }}-qr.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
});

function downloadSideQR() {
    const canvas = document.querySelector('#sideQr canvas');
    if (!canvas) return;

    const link = document.createElement('a');
    link.download = '{{ $container->container_id }}-qr.png';
    link.href = canvas.toDataURL('image/png');
    link.click();
}
</script>
@endsection