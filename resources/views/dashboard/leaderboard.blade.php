@extends('layouts.app')

@section('title', 'Driver Leaderboard')
@section('body-class', 'page-dashboard')

@section('content')
<style>
    /* Leaderboard Layout */
    .leaderboard-podium {
        display: grid;
        grid-template-columns: 1fr 1.2fr 1fr;
        gap: 20px;
        align-items: end;
        margin-bottom: 40px;
        margin-top: 20px;
    }

    /* Podium Cards */
    .podium-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        position: relative;
        border: 1px solid #e5e7eb;
    }

    .podium-card--1 { 
        padding: 40px 24px; 
        border-top: 5px solid #FFD700; /* Gold */
        z-index: 2;
    }
    .podium-card--2 { border-top: 5px solid #C0C0C0; /* Silver */ }
    .podium-card--3 { border-top: 5px solid #CD7F32; /* Bronze */ }

    /* Rank Badges on Podium */
    .podium-rank {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: white;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .rank-1 { background: #FFD700; color: #000; }
    .rank-2 { background: #C0C0C0; color: #000; }
    .rank-3 { background: #CD7F32; }

    /* Avatar Logic */
    .driver-avatar {
        width: 64px;
        height: 64px;
        background: #f3f4f6;
        color: #3b82f6;
        border-radius: 50%;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 700;
        border: 2px solid white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .driver-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Score Badges */
    .score-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        background: #ecfdf5;
        color: #047857;
        font-weight: 700;
        margin-top: 8px;
    }

    /* Progress Bars in Table */
    .mini-progress {
        height: 6px;
        background: #f3f4f6;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 4px;
        width: 100px;
    }
    .mini-progress-bar {
        height: 100%;
        border-radius: 3px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .leaderboard-podium { grid-template-columns: 1fr; }
        .podium-card--1 { order: -1; }
    }
</style>

<section class="page">
    <div class="page-head">
        <div>
            <div class="breadcrumb">Operations <span>/ Leaderboard</span></div>
            <h1>Performance Rankings</h1>
            <p class="page-head__sub">Top performing drivers based on safety, efficiency, and punctuality.</p>
        </div>
    </div>

    {{-- ===================== TOP 3 PODIUM ===================== --}}
    @php 
        $top3 = $leaderboard->take(3)->values(); 
    @endphp

    <div class="leaderboard-podium">
        {{-- Rank 2 - Silver --}}
        @if(isset($top3[1]))
        <div class="podium-card podium-card--2">
            <div class="podium-rank rank-2">2</div>
            <div class="driver-avatar">
                @if($top3[1]['driver']->driver_photo)
                    <img src="{{ asset('storage/' . $top3[1]['driver']->driver_photo) }}" alt="Driver">
                @else
                    {{ strtoupper(substr($top3[1]['driver']->full_name, 0, 1)) }}
                @endif
            </div>
            <h3 style="margin-bottom:4px; font-size: 1.1rem;">{{ $top3[1]['driver']->full_name }}</h3>
            <div class="score-badge">{{ $top3[1]['final_score'] }} Pts</div>
        </div>
        @endif

        {{-- Rank 1 - Gold --}}
        @if(isset($top3[0]))
        <div class="podium-card podium-card--1">
            <div class="podium-rank rank-1">1</div>
            <div class="driver-avatar" style="width:85px; height:85px; font-size: 32px;">
                @if($top3[0]['driver']->image)
                    <img src="{{ asset('storage/' . $top3[0]['driver']->image) }}" alt="Driver">
                @else
                    {{ strtoupper(substr($top3[0]['driver']->full_name, 0, 1)) }}
                @endif
            </div>
            <h2 style="margin-bottom:4px; font-size: 1.5rem;">{{ $top3[0]['driver']->full_name }}</h2>
            <div class="score-badge" style="font-size: 1.1rem; padding: 6px 16px;">{{ $top3[0]['final_score'] }} Pts</div>
            <p style="font-size: 12px; color: #6b7280; margin-top: 10px;">👑 Top Performer</p>
        </div>
        @endif

        {{-- Rank 3 - Bronze --}}
        @if(isset($top3[2]))
        <div class="podium-card podium-card--3">
            <div class="podium-rank rank-3">3</div>
            <div class="driver-avatar">
                @if($top3[2]['driver']->image)
                    <img src="{{ asset('storage/' . $top3[2]['driver']->image) }}" alt="Driver">
                @else
                    {{ strtoupper(substr($top3[2]['driver']->full_name, 0, 1)) }}
                @endif
            </div>
            <h3 style="margin-bottom:4px; font-size: 1.1rem;">{{ $top3[2]['driver']->full_name }}</h3>
            <div class="score-badge">{{ $top3[2]['final_score'] }} Pts</div>
        </div>
        @endif
    </div>

    {{-- ===================== FULL RANKINGS TABLE ===================== --}}
    <div class="card">
        <div class="card__head">
            <h3>Full Driver Standings</h3>
        </div>

        <div class="table-wrap">
            <table class="data">
                <thead>
                    <tr>
                        <th width="80">Rank</th>
                        <th>Driver</th>
                        <th>On-Time Delivery</th>
                        <th>Safety Score</th>
                        <th>Fuel Efficiency</th>
                        <th style="text-align:right">Total Points</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leaderboard as $index => $row)
                        <tr>
                            <td>
                                <span class="badge {{ $index < 3 ? 'badge--primary' : 'badge--ghost' }}">
                                    #{{ $index + 1 }}
                                </span>
                            </td>
                            <td>
                                <div style="display:flex; align-items:center; gap:12px;">
                                    <div class="driver-avatar" style="width:36px; height:36px; font-size:14px; margin:0; flex-shrink:0;">
                                        @if($row['driver']->driver_photo)
                                            <img src="{{ asset('storage/' . $row['driver']->driver_photo) }}" alt="Avatar">
                                        @else
                                            {{ strtoupper(substr($row['driver']->full_name, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div style="display: flex; flex-direction: column;">
                                        <strong style="color: #111827;">{{ $row['driver']->full_name }}</strong>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:12px; font-weight: 600;">{{ $row['on_time_score'] }}%</div>
                                <div class="mini-progress">
                                    <div class="mini-progress-bar" style="width: {{ $row['on_time_score'] }}%; background: #10b981;"></div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:12px; font-weight: 600;">{{ $row['incident_score'] }}/100</div>
                                <div class="mini-progress">
                                    <div class="mini-progress-bar" style="width: {{ $row['incident_score'] }}%; background: {{ $row['incident_score'] < 70 ? '#ef4444' : '#3b82f6' }};"></div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size:12px; font-weight: 600;">{{ $row['fuel_score'] }}/100</div>
                                <div class="mini-progress">
                                    <div class="mini-progress-bar" style="width: {{ $row['fuel_score'] }}%; background: #f59e0b;"></div>
                                </div>
                            </td>
                            <td style="text-align:right">
                                <strong style="color: #2563eb; font-size: 1.1rem;">
                                    {{ round($row['final_score'], 1) }}
                                </strong>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:50px; color: #6b7280;">
                                No driver performance data recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection