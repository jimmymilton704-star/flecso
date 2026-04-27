@extends('layouts.app')

@section('title', 'Flecso Dashboard')
@section('body-class', 'page-dashboard')

@section('content')

    <section class="page dash-greeting">
        <div class="page-head">
            <div>
                <div class="breadcrumb">Overview <span>/ Dashboard</span></div>
                <h1>
                    Good {{ now()->format('H') < 12 ? 'morning' : (now()->format('H') < 18 ? 'afternoon' : 'evening') }},
                    {{ auth()->user()->name }} 👋
                </h1>

                <div class="page-head__sub">
                    Here's what's happening across your fleet right now — {{ now()->format('l, F d, Y') }}.
                </div>
            </div>
            <div class="page-head__actions">
                <button class="btn btn--ghost"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <path d="M16 2v4M8 2v4M3 10h18" />
                    </svg> Last 30 days</button>
                <button class="btn btn--ghost"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" />
                    </svg> Export report</button>
                <button class="btn btn--primary"><svg viewBox="0 0 24 24" width="16" height="16" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14" />
                    </svg> Quick add</button>
            </div>
        </div>

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat">
                <div class="stat__icon stat__icon--orange"><svg viewBox="0 0 24 24" width="22" height="22" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M3 7h10v10H3z" />
                        <path d="M13 10h5l3 3v4h-8" />
                        <circle cx="7" cy="18" r="2" />
                        <circle cx="17" cy="18" r="2" />
                    </svg></div>
                <div class="stat__label">Total Trucks</div>
                <div class="stat__value">{{ $total_trucks }}</div>
                <div class="stat__trend trend-up">▲ 4.2% vs last month</div>
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34">
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#FF6B1A" stroke-width="2"
                        fill="none" stroke-linecap="round" />
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4 L90 34 L0 34 Z" fill="#FF6B1A"
                        opacity=".08" />
                </svg>
            </div>
            <div class="stat">
                <div class="stat__icon stat__icon--dark"><svg viewBox="0 0 24 24" width="22" height="22" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <rect x="3" y="7" width="18" height="11" rx="1.5" />
                        <path d="M7 7v11M12 7v11M17 7v11" />
                    </svg></div>
                <div class="stat__label">Total Containers</div>
                <div class="stat__value">{{ $total_containers }}</div>
                <div class="stat__trend trend-up">▲ 1.8% vs last month</div>
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34">
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#111114" stroke-width="2"
                        fill="none" stroke-linecap="round" />
                </svg>
            </div>
            <div class="stat">
                <div class="stat__icon stat__icon--green"><svg viewBox="0 0 24 24" width="22" height="22" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="8" r="4" />
                        <path d="M4 21c0-4 4-7 8-7s8 3 8 7" />
                    </svg></div>
                <div class="stat__label">Total Drivers</div>
                <div class="stat__value">{{ $total_drivers }}</div>
                <div class="stat__trend trend-up">▲ 2.5% vs last month</div>
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34">
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#10B981" stroke-width="2"
                        fill="none" stroke-linecap="round" />
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4 L90 34 L0 34 Z" fill="#10B981"
                        opacity=".08" />
                </svg>
            </div>
            <div class="stat">
                <div class="stat__icon stat__icon--blue"><svg viewBox="0 0 24 24" width="22" height="22" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="m8 13 4-4 4 4-4 4z" />
                        <path d="M12 2v4M5 7l2.5 2.5M19 7l-2.5 2.5M12 22a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
                    </svg></div>
                <div class="stat__label">Active Trips</div>
                <div class="stat__value">{{ $active_trips }}</div>
                <div class="stat__trend trend-down">▼ 1.1% vs last month</div>
                <svg class="stat__spark" width="90" height="34" viewBox="0 0 90 34">
                    <path d="M0 24 L12 18 L24 22 L36 14 L48 18 L60 8 L72 12 L90 4" stroke="#3B82F6" stroke-width="2"
                        fill="none" stroke-linecap="round" />
                </svg>
            </div>
        </div>

        <!-- Chart + SOS -->
        <div class="grid-2">
            <div class="card">
                <div class="card__head">
                    <div class="card__title">
                        <h3>Trip Activity Overview</h3>
                    </div>
                    <div class="chart-legend">
                        <span><i class="dot" style="background:#10B981"></i> Completed</span>
                        <span><i class="dot" style="background:#FF6B1A"></i> Ongoing</span>
                        <span><i class="dot" style="background:#F59E0B"></i> Delayed</span>
                        <span><i class="dot" style="background:#EF4444"></i> Cancelled</span>
                    </div>
                </div>
                <div class="card__body">
                    <div class="chart-wrap"><canvas id="tripChart"></canvas></div>
                </div>
            </div>

            <div class="card">
                <div class="card__head">
                    <div class="card__title">
                        <div
                            style="width:30px;height:30px;border-radius:10px;background:var(--danger-50);color:var(--danger-700);display:grid;place-items:center">
                            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 0 1-3.46 0" />
                            </svg>
                        </div>
                        <h3>SOS Alerts</h3>
                    </div>
                    <button class="btn btn--sm btn--ghost">View all</button>
                </div>
                <div class="card__body">
                    @forelse($sos_alerts as $sos)
                        <div class="activity-item" style="margin-bottom:12px">
                            <div>
                                <strong>{{ $sos->driver->name ?? 'N/A' }}</strong>
                                <div style="font-size:12px;color:#999">
                                    Trip #{{ $sos->trip->id ?? '-' }}
                                </div>
                            </div>

                            <div style="font-size:12px;color:red;font-weight:600">
                                {{ strtoupper($sos->status) }}
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;color:#888">No SOS alerts</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Mini stats -->
        <div class="grid-3">
            <div class="card">
                <div class="card__body">
                    <div class="stat__label">Completed Trips</div>
                    <div class="stat__value" style="font-size:26px">{{ $completed_trips }}</div>
                    <div
                        style="display:inline-block;margin-top:8px;font-size:12px;font-weight:600;padding:3px 10px;border-radius:999px;background:var(--success-50);color:var(--success-700)">
                        +12 this week</div>
                </div>
            </div>
            <div class="card">
                <div class="card__body">
                    <div class="stat__label">Ongoing Trips</div>
                    <div class="stat__value" style="font-size:26px">{{ $ongoing_trips }}</div>
                    <div
                        style="display:inline-block;margin-top:8px;font-size:12px;font-weight:600;padding:3px 10px;border-radius:999px;background:var(--orange-50);color:var(--orange-600)">
                        Live now</div>
                </div>
            </div>
            <div class="card">
                <div class="card__body">
                    <div class="stat__label">Cancelled Trips</div>
                    <div class="stat__value" style="font-size:26px">{{ $cancelled_trips }}</div>
                    <div
                        style="display:inline-block;margin-top:8px;font-size:12px;font-weight:600;padding:3px 10px;border-radius:999px;background:var(--ink-100);color:var(--ink-500)">
                        -2 vs last week</div>
                </div>
            </div>
        </div>

        <!-- Map + Activity -->
        <div class="grid-live">
            <div class="card" style="padding:14px">
                <div class="card__head" style="padding:10px 14px 14px">
                    <div class="card__title">
                        <h3>Live Fleet Tracking</h3>
                    </div>
                    <div class="chart-legend">
                        <span><i class="dot" style="background:#FF6B1A"></i> On route</span>
                        <span><i class="dot" style="background:#F59E0B"></i> Delayed</span>
                    </div>
                </div>
                <div class="map-wrap">
                    <div id="mapEl"></div>
                    <div class="map-overlay-stat">
                        <div class="pill"><strong>{{ $active_trips }}</strong><em>Active</em></div>
                        <div class="pill"><strong>{{ $completed_trips }}</strong><em>Completed</em></div>
                        <div class="pill"><strong>94%</strong><em>On-time</em></div>
                    </div>
                    <div class="map-legend">
                        <span><i class="dot" style="background:#FF6B1A"></i> Active trucks</span>
                        <span><i class="dot" style="background:#F59E0B"></i> Delayed</span>
                        <span><i class="dot" style="background:#10B981"></i> Completed</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card__head">
                    <div class="card__title">
                        <h3>Recent Activity</h3>
                    </div>
                    <button class="btn btn--sm btn--ghost">See all</button>
                </div>
                <div class="card__body activity-list">
                    @forelse($recent_trips as $trip)
                        <div class="activity-item" style="margin-bottom:12px">
                            <div>
                                <strong>{{ $trip->driver->name ?? 'N/A' }}</strong>
                                <div style="font-size:12px;color:#999">
                                    {{ ucfirst($trip->trip_status) }}
                                </div>
                            </div>

                            <div style="font-size:12px;color:#888">
                                {{ \Carbon\Carbon::parse($trip->schedule_datetime)->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center;color:#888">No recent activity</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Schedule -->
        <div class="card">
            <div class="card__head">
                <div class="card__title">
                    <h3>Upcoming & Recent Trips</h3>
                </div>
                <div class="flex gap-8">
                    <button class="btn btn--sm btn--ghost"><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <path d="M16 2v4M8 2v4M3 10h18" />
                        </svg> Calendar view</button>
                    <button class="btn btn--sm btn--primary"><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14" />
                        </svg> Schedule trip</button>
                </div>
            </div>
            <div class="card__body">
                @forelse($all_trips as $trip)
                    <div class="activity-item" style="margin-bottom:12px">
                        <div>
                            <strong>Trip #{{ $trip->id }}</strong>
                            <div style="font-size:12px;color:#999">
                                Driver: {{ $trip->driver->name ?? 'N/A' }}
                            </div>
                        </div>

                        <div style="font-size:12px;color:#888">
                            {{ $trip->trip_status }}
                        </div>
                    </div>
                @empty
                    <div style="text-align:center;color:#888">No trips found</div>
                @endforelse
            </div>
        </div>
    </section>

@endsection