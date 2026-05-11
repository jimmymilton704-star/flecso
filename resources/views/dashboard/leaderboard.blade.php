@extends('layouts.app')

@section('title', 'Driver Leaderboard')
@section('body-class', 'page-dashboard')

@section('content')
<style>
    :root {
        --lb-bg: #f8fafc;
        --lb-card: rgba(255,255,255,0.92);
        --lb-border: #e5e7eb;
        --lb-text: #0f172a;
        --lb-muted: #64748b;
        --lb-primary: #2563eb;
        --lb-success: #10b981;
        --lb-warn: #f59e0b;
        --lb-danger: #ef4444;
    }

    .lb-hero {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        margin-bottom: 24px;
        padding: 24px;
        border: 1px solid var(--lb-border);
        border-radius: 24px;
        background:
            radial-gradient(circle at top right, rgba(37,99,235,0.08), transparent 35%),
            linear-gradient(180deg, rgba(255,255,255,0.98), rgba(248,250,252,0.96));
        box-shadow: 0 20px 50px rgba(15,23,42,0.06);
    }

    .lb-title {
        font-size: 2rem;
        line-height: 1.15;
        color: var(--lb-text);
        margin: 6px 0 8px;
        font-weight: 800;
        letter-spacing: -0.03em;
    }

    .lb-subtitle {
        color: var(--lb-muted);
        max-width: 760px;
        font-size: 0.98rem;
    }

    .lb-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 14px;
        border-radius: 999px;
        background: #eff6ff;
        color: var(--lb-primary);
        font-size: 0.88rem;
        font-weight: 700;
        border: 1px solid #dbeafe;
    }

    .lb-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        flex-wrap: wrap;
    }

    .lb-summary {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
        margin: 18px 0 30px;
    }

    .lb-summary-card {
        background: rgba(255,255,255,0.95);
        border: 1px solid var(--lb-border);
        border-radius: 20px;
        padding: 18px;
        box-shadow: 0 10px 30px rgba(15,23,42,0.04);
    }

    .lb-summary-label {
        font-size: 0.82rem;
        color: var(--lb-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
    }

    .lb-summary-value {
        font-size: 1.7rem;
        font-weight: 800;
        color: var(--lb-text);
        margin-top: 8px;
    }

    .lb-summary-note {
        margin-top: 8px;
        font-size: 0.9rem;
        color: var(--lb-muted);
    }

    .leaderboard-podium {
        display: grid;
        grid-template-columns: 1fr 1.2fr 1fr;
        gap: 18px;
        align-items: end;
        margin-bottom: 34px;
    }

    .podium-card {
        background: var(--lb-card);
        border-radius: 24px;
        padding: 22px;
        text-align: center;
        box-shadow: 0 16px 40px rgba(15,23,42,0.07);
        position: relative;
        border: 1px solid var(--lb-border);
        backdrop-filter: blur(10px);
    }

    .podium-card--1 {
        padding: 34px 24px;
        border-top: 5px solid #facc15;
        transform: translateY(-6px);
        z-index: 2;
    }

    .podium-card--2 {
        border-top: 5px solid #cbd5e1;
    }

    .podium-card--3 {
        border-top: 5px solid #d97706;
    }

    .podium-rank {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        width: 34px;
        height: 34px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        color: white;
        box-shadow: 0 10px 18px rgba(15,23,42,0.14);
        font-size: 0.9rem;
    }

    .rank-1 { background: linear-gradient(135deg, #facc15, #f59e0b); color: #111827; }
    .rank-2 { background: linear-gradient(135deg, #cbd5e1, #94a3b8); color: #111827; }
    .rank-3 { background: linear-gradient(135deg, #d97706, #b45309); }

    .driver-avatar {
        width: 68px;
        height: 68px;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        color: #2563eb;
        border-radius: 50%;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        font-weight: 800;
        border: 3px solid white;
        box-shadow: 0 10px 22px rgba(37,99,235,0.12);
        overflow: hidden;
    }

    .driver-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .driver-name {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--lb-text);
        margin-bottom: 6px;
        letter-spacing: -0.02em;
    }

    .podium-score {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        background: #ecfdf5;
        color: #047857;
        font-weight: 800;
        font-size: 0.95rem;
    }

    .podium-meta {
        margin-top: 10px;
        color: var(--lb-muted);
        font-size: 0.82rem;
    }

    .card--leaderboard {
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid var(--lb-border);
        box-shadow: 0 16px 40px rgba(15,23,42,0.06);
    }

    .card--leaderboard .card__head {
        padding: 18px 22px;
        background: linear-gradient(180deg, #ffffff, #f8fafc);
        border-bottom: 1px solid var(--lb-border);
    }

    .card--leaderboard .card__head h3 {
        font-size: 1.05rem;
        font-weight: 800;
        color: var(--lb-text);
        margin: 0;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table.data {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }

    table.data thead th {
        background: #f8fafc;
        color: var(--lb-muted);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 14px 18px;
        border-bottom: 1px solid var(--lb-border);
        white-space: nowrap;
    }

    table.data tbody td {
        padding: 16px 18px;
        border-bottom: 1px solid #eef2f7;
        vertical-align: middle;
        color: var(--lb-text);
    }

    table.data tbody tr {
        transition: transform .18s ease, background .18s ease, box-shadow .18s ease;
    }

    table.data tbody tr:hover {
        background: #f8fbff;
    }

    .rank-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 42px;
        padding: 6px 12px;
        border-radius: 999px;
        background: #eff6ff;
        color: #1d4ed8;
        font-weight: 800;
        font-size: 0.86rem;
    }

    .driver-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .driver-avatar--sm {
        width: 38px;
        height: 38px;
        font-size: 14px;
        margin: 0;
        flex-shrink: 0;
        border-width: 2px;
    }

    .driver-text {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .driver-text strong {
        font-size: 0.96rem;
        color: var(--lb-text);
    }

    .driver-text span {
        font-size: 0.82rem;
        color: var(--lb-muted);
    }

    .score-wrap {
        min-width: 120px;
    }

    .score-value {
        font-size: 0.82rem;
        font-weight: 800;
        color: var(--lb-text);
        margin-bottom: 5px;
    }

    .mini-progress {
        height: 7px;
        background: #e5e7eb;
        border-radius: 999px;
        overflow: hidden;
        width: 110px;
    }

    .mini-progress-bar {
        height: 100%;
        border-radius: 999px;
    }

    .total-score {
        text-align: right;
        font-size: 1.15rem;
        font-weight: 900;
        color: #2563eb;
        letter-spacing: -0.02em;
    }

    .empty-state {
        text-align: center;
        padding: 54px 24px;
        color: var(--lb-muted);
    }

    @media (max-width: 1024px) {
        .lb-summary {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 768px) {
        .lb-hero {
            flex-direction: column;
        }

        .lb-summary {
            grid-template-columns: 1fr;
        }

        .leaderboard-podium {
            grid-template-columns: 1fr;
        }

        .podium-card--1 {
            order: -1;
            transform: none;
        }

        .total-score {
            text-align: left;
        }
    }
</style>

<section class="page">

    <div class="lb-hero">
        <div>
            <div class="lb-badge">Driver Performance Leaderboard</div>
            <div class="lb-title">Top performing drivers across punctuality, safety, and fuel efficiency</div>
            <div class="lb-subtitle">
                A live ranking view that helps identify your best drivers, highlight training needs, and reward consistent performance.
            </div>
        </div>

        <div class="lb-actions">
            <button class="btn btn--ghost" id="exportLeaderboardCsv">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12" />
                </svg>
                Export CSV
            </button>
        </div>
    </div>

    @php
        $top3 = $leaderboard->take(3)->values();
        $avgScore = $leaderboard->count() ? round($leaderboard->avg('final_score'), 1) : 0;
        $bestScore = $leaderboard->count() ? round($leaderboard->first()['final_score'] ?? 0, 1) : 0;
        $topDrivers = $leaderboard->take(3)->count();
        $totalDrivers = $leaderboard->count();
    @endphp

    <div class="lb-summary">
        <div class="lb-summary-card">
            <div class="lb-summary-label">Total Drivers</div>
            <div class="lb-summary-value">{{ $totalDrivers }}</div>
            <div class="lb-summary-note">Ranked in this cycle</div>
        </div>

        <div class="lb-summary-card">
            <div class="lb-summary-label">Average Score</div>
            <div class="lb-summary-value">{{ $avgScore }}</div>
            <div class="lb-summary-note">Across the fleet</div>
        </div>

        <div class="lb-summary-card">
            <div class="lb-summary-label">Top Score</div>
            <div class="lb-summary-value">{{ $bestScore }}</div>
            <div class="lb-summary-note">Best performing driver</div>
        </div>

        <div class="lb-summary-card">
            <div class="lb-summary-label">Podium Spots</div>
            <div class="lb-summary-value">{{ $topDrivers }}</div>
            <div class="lb-summary-note">Gold, silver, bronze</div>
        </div>
    </div>

    {{-- ===================== TOP 3 PODIUM ===================== --}}
    <div class="leaderboard-podium">

        @if (isset($top3[1]))
            <div class="podium-card podium-card--2">
                <div class="podium-rank rank-2">2</div>
                <div class="driver-avatar">
                    @if (!empty($top3[1]['driver']->driver_photo))
                        <img src="{{ asset('storage/' . $top3[1]['driver']->driver_photo) }}" alt="Driver">
                    @else
                        {{ strtoupper(substr($top3[1]['driver']->full_name ?? 'D', 0, 1)) }}
                    @endif
                </div>
                <div class="driver-name">{{ $top3[1]['driver']->full_name ?? '-' }}</div>
                <div class="podium-score">{{ $top3[1]['final_score'] }} pts</div>
                <div class="podium-meta">Strong overall consistency</div>
            </div>
        @endif

        @if (isset($top3[0]))
            <div class="podium-card podium-card--1">
                <div class="podium-rank rank-1">1</div>
                <div class="driver-avatar" style="width:86px;height:86px;font-size:32px;">
                    @if (!empty($top3[0]['driver']->driver_photo))
                        <img src="{{ asset('storage/' . $top3[0]['driver']->driver_photo) }}" alt="Driver">
                    @else
                        {{ strtoupper(substr($top3[0]['driver']->full_name ?? 'D', 0, 1)) }}
                    @endif
                </div>
                <div class="driver-name" style="font-size:1.25rem;">{{ $top3[0]['driver']->full_name ?? '-' }}</div>
                <div class="podium-score" style="font-size:1rem;padding:8px 16px;">{{ $top3[0]['final_score'] }} pts</div>
                <div class="podium-meta">👑 Top performer this cycle</div>
            </div>
        @endif

        @if (isset($top3[2]))
            <div class="podium-card podium-card--3">
                <div class="podium-rank rank-3">3</div>
                <div class="driver-avatar">
                    @if (!empty($top3[2]['driver']->driver_photo))
                        <img src="{{ asset('storage/' . $top3[2]['driver']->driver_photo) }}" alt="Driver">
                    @else
                        {{ strtoupper(substr($top3[2]['driver']->full_name ?? 'D', 0, 1)) }}
                    @endif
                </div>
                <div class="driver-name">{{ $top3[2]['driver']->full_name ?? '-' }}</div>
                <div class="podium-score">{{ $top3[2]['final_score'] }} pts</div>
                <div class="podium-meta">Reliable and steady</div>
            </div>
        @endif

    </div>

    {{-- ===================== FULL RANKINGS TABLE ===================== --}}
    <div class="card card--leaderboard">
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
                                <span class="rank-pill">#{{ $index + 1 }}</span>
                            </td>

                            <td>
                                <div class="driver-cell">
                                    <div class="driver-avatar driver-avatar--sm">
                                        @if (!empty($row['driver']->driver_photo))
                                            <img src="{{ asset('storage/' . $row['driver']->driver_photo) }}" alt="Avatar">
                                        @else
                                            {{ strtoupper(substr($row['driver']->full_name ?? 'D', 0, 1)) }}
                                        @endif
                                    </div>

                                    <div class="driver-text">
                                        <strong>{{ $row['driver']->full_name ?? '-' }}</strong>
                                        <span>Driver ID: {{ $row['driver']->id ?? '-' }}</span>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="score-wrap">
                                    <div class="score-value">{{ $row['on_time_score'] }}%</div>
                                    <div class="mini-progress">
                                        <div class="mini-progress-bar" style="width: {{ $row['on_time_score'] }}%; background: #10b981;"></div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="score-wrap">
                                    <div class="score-value">{{ $row['incident_score'] }}/100</div>
                                    <div class="mini-progress">
                                        <div class="mini-progress-bar" style="width: {{ $row['incident_score'] }}%; background: {{ $row['incident_score'] < 70 ? '#ef4444' : '#3b82f6' }};"></div>
                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="score-wrap">
                                    <div class="score-value">{{ $row['fuel_score'] }}/100</div>
                                    <div class="mini-progress">
                                        <div class="mini-progress-bar" style="width: {{ $row['fuel_score'] }}%; background: #f59e0b;"></div>
                                    </div>
                                </div>
                            </td>

                            <td class="total-score">
                                {{ round($row['final_score'], 1) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    No driver performance data recorded yet.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const exportBtn = document.getElementById('exportLeaderboardCsv');

        if (!exportBtn) return;

        exportBtn.addEventListener('click', function () {
            const csv = [];

            csv.push([
                'Rank',
                'Driver',
                'On-Time Delivery',
                'Safety Score',
                'Fuel Efficiency',
                'Total Points'
            ].join(','));

            document.querySelectorAll('table.data tbody tr').forEach((row) => {
                const cols = row.querySelectorAll('td');
                if (cols.length < 6) return;

                const rowData = [
                    cols[0].innerText.trim().replace(/\n/g, ' '),
                    cols[1].innerText.trim().replace(/\n/g, ' '),
                    cols[2].innerText.trim().replace(/\n/g, ' '),
                    cols[3].innerText.trim().replace(/\n/g, ' '),
                    cols[4].innerText.trim().replace(/\n/g, ' '),
                    cols[5].innerText.trim().replace(/\n/g, ' ')
                ];

                csv.push(rowData.join(','));
            });

            const blob = new Blob([csv.join('\n')], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);

            const a = document.createElement('a');
            a.href = url;
            a.download = 'driver_leaderboard.csv';
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);
        });
    });
</script>
@endsection