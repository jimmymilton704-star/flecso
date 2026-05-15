@extends('layouts.app')

@section('title', 'Activity Logs')
@section('body-class', 'page-dashboard')

@section('content')
    <style>
        /* ══ HEADER ══════════════════════════════════════════════ */
        .al-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .al-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ══ STAT CARDS ══════════════════════════════════════════ */
        .al-stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 22px;
        }

        @media(max-width:900px) {
            .al-stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media(max-width:500px) {
            .al-stats {
                grid-template-columns: 1fr;
            }
        }

        .al-stat-card {
            background: white;
            border: 1px solid var(--surface-line);
            border-radius: 14px;
            padding: 22px 22px 16px;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .al-stat-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .al-stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
        }

        .al-stat-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--ink-400);
            margin-bottom: 4px;
        }

        .al-stat-val {
            font-size: 32px;
            font-weight: 800;
            color: var(--ink-900);
            line-height: 1;
            letter-spacing: -1px;
        }

        .al-stat-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 4px;
        }

        .al-stat-trend {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 11px;
            font-weight: 600;
            border-radius: 20px;
            padding: 2px 8px;
        }

        .al-stat-trend.up {
            background: #e6f9ee;
            color: #1a7a35;
        }

        .al-stat-trend.down {
            background: #fdecea;
            color: #c0392b;
        }

        .al-stat-trend.neu {
            background: var(--ink-50);
            color: var(--ink-500);
        }

        .al-spark {
            display: block;
        }

        /* ══ FILTER BAR ══════════════════════════════════════════ */
        .al-filter-bar {
            background: white;
            border: 1px solid var(--surface-line);
            border-radius: 12px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }

        .al-filter-left {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            flex: 1;
        }

        .al-filter-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
        }

        .al-search-wrap {
            position: relative;
            min-width: 240px;
        }

        .al-search-wrap svg {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: var(--ink-400);
        }

        .al-search-wrap .input {
            padding-left: 32px;
            height: 34px;
            font-size: 13px;
        }

        .al-tabs {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }

        .al-tab {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            border: 1px solid transparent;
            background: transparent;
            color: var(--ink-500);
            cursor: pointer;
            text-decoration: none;
            transition: all .15s;
        }

        .al-tab:hover {
            background: var(--ink-50);
            color: var(--ink-800);
        }

        .al-tab.active {
            background: var(--ink-900);
            color: #fff;
            border-color: var(--ink-900);
        }

        .al-tab.post.active {
            background: #1a7a35;
            border-color: #1a7a35;
        }

        .al-tab.put.active {
            background: #b36200;
            border-color: #b36200;
        }

        .al-tab.delete.active {
            background: #c0392b;
            border-color: #c0392b;
        }

        /* ══ FEED ════════════════════════════════════════════════ */
        .al-feed {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: 8px;
        }

        .al-log-card {
            background: white;
            border: 1px solid var(--surface-line);
            border-radius: 16px;
            padding: 16px 18px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            transition: all .15s;
        }

        .al-log-card:hover {
            border-color: var(--ink-300);
            box-shadow: 0 8px 24px rgba(15, 23, 42, .04);
        }

        .al-log-left {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            min-width: 0;
            flex: 1;
        }

        .al-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--ink-900);
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            overflow: hidden;
        }

        .al-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .al-log-content {
            min-width: 0;
            flex: 1;
        }

        .al-log-sentence {
            font-size: 15px;
            font-weight: 600;
            color: var(--ink-900);
            line-height: 1.45;
            word-break: break-word;
        }

        .al-log-time {
            margin-top: 4px;
            font-size: 12px;
            color: var(--ink-400);
        }

        .al-log-right {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .al-method {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
            font-family: monospace;
            letter-spacing: .03em;
        }

        .al-method.post {
            background: #e6f9ee;
            color: #1a7a35;
        }

        .al-method.put {
            background: #fff4e0;
            color: #b36200;
        }

        .al-method.delete {
            background: #fdecea;
            color: #c0392b;
        }

        .al-method.visit {
            background: var(--ink-100);
            color: var(--ink-600);
        }

        .al-btn-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            border: none;
            background: transparent;
            color: var(--ink-400);
            cursor: pointer;
            transition: all .12s;
        }

        .al-btn-icon:hover {
            background: var(--ink-100);
            color: var(--ink-800);
        }

        .al-empty,
        .al-client-empty {
            text-align: center;
            padding: 60px 20px;
            color: var(--ink-400);
        }

        .al-empty svg,
        .al-client-empty svg {
            opacity: .2;
            margin-bottom: 12px;
            display: block;
            margin-inline: auto;
        }

        .al-client-empty {
            display: none;
        }


        /* ══ MODAL ═══════════════════════════════════════════════ */
        #al-modal {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 999999;
            align-items: center;
            justify-content: center;
        }

        #al-modal .al-modal-backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .55);
            backdrop-filter: blur(2px);
        }

        #al-modal .al-modal-box {
            position: relative;
            background: white;
            border: 1px solid var(--surface-line);
            border-radius: 16px;
            padding: 24px;
            max-width: 680px;
            width: min(680px, 92vw);
            max-height: 80vh;
            overflow-y: auto;
            z-index: 1;
            box-shadow: 0 24px 80px rgba(0, 0, 0, .28);
        }

        #al-modal pre {
            font-size: 12px;
            font-family: monospace;
            color: var(--ink-600);
            white-space: pre-wrap;
            word-break: break-all;
            background: var(--ink-50);
            border-radius: 8px;
            padding: 14px;
            border: 1px solid var(--surface-line);
            margin: 0;
        }
    </style>

    <section class="page">

        <div class="al-header">
            <div>
                <div class="breadcrumb">System <span>/ Activity Logs</span></div>
                <h1>Activity Logs</h1>
                <div class="page-head__sub">A human readable trail of what users did on the platform.</div>
            </div>
            <div class="al-header-actions">
                <button type="button" class="btn btn--ghost btn--sm" id="clearFiltersTopBtn">
                    Reset
                </button>
            </div>
        </div>

        @php
            $totalLogs = $logs->total();
            $todayAll = \App\Models\ActivityLog::where('user_id', auth()->id())
                ->whereDate('created_at', today())
                ->count();
            $todayPost = \App\Models\ActivityLog::where('user_id', auth()->id())
                ->where('method', 'POST')
                ->whereDate('created_at', today())
                ->count();
            $todayDelete = \App\Models\ActivityLog::where('user_id', auth()->id())
                ->where('method', 'DELETE')
                ->whereDate('created_at', today())
                ->count();
        @endphp

        <div class="al-stats">
            <div class="al-stat-card">
                <div class="al-stat-top">
                    <div>
                        <div class="al-stat-label">Total Events</div>
                        <div class="al-stat-val">{{ number_format($totalLogs) }}</div>
                    </div>
                    <div class="al-stat-icon" style="background:var(--ink-900);color:#fff">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                            <rect x="9" y="3" width="6" height="4" rx="1" />
                            <path d="M9 12h6M9 16h4" />
                        </svg>
                    </div>
                </div>
                <div class="al-stat-bottom">
                    <span class="al-stat-trend up">All time</span>
                    <svg class="al-spark" viewBox="0 0 80 28" width="80" height="28" fill="none">
                        <polyline points="0,24 14,18 28,20 42,12 56,15 70,8 80,10" stroke="var(--ink-400)"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>

            <div class="al-stat-card">
                <div class="al-stat-top">
                    <div>
                        <div class="al-stat-label">Today</div>
                        <div class="al-stat-val">{{ $todayAll }}</div>
                    </div>
                    <div class="al-stat-icon" style="background:#e6f9ee;color:#1a7a35">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <path d="M16 2v4M8 2v4M3 10h18" />
                        </svg>
                    </div>
                </div>
                <div class="al-stat-bottom">
                    <span class="al-stat-trend up">Active today</span>
                    <svg class="al-spark" viewBox="0 0 80 28" width="80" height="28" fill="none">
                        <polyline points="0,22 14,16 28,18 42,10 56,14 70,6 80,9" stroke="#1a7a35" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>

            <div class="al-stat-card">
                <div class="al-stat-top">
                    <div>
                        <div class="al-stat-label">Creates Today</div>
                        <div class="al-stat-val">{{ $todayPost }}</div>
                    </div>
                    <div class="al-stat-icon" style="background:#e6f9ee;color:#1a7a35">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                    </div>
                </div>
                <div class="al-stat-bottom">
                    <span class="al-stat-trend neu">POST actions</span>
                    <svg class="al-spark" viewBox="0 0 80 28" width="80" height="28" fill="none">
                        <polyline points="0,20 14,22 28,14 42,16 56,10 70,12 80,7" stroke="#1a7a35" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>

            <div class="al-stat-card">
                <div class="al-stat-top">
                    <div>
                        <div class="al-stat-label">Deletes Today</div>
                        <div class="al-stat-val">{{ $todayDelete }}</div>
                    </div>
                    <div class="al-stat-icon" style="background:#fdecea;color:#c0392b">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6M10 11v6M14 11v6" />
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                        </svg>
                    </div>
                </div>
                <div class="al-stat-bottom">
                    <span class="al-stat-trend down">DELETE actions</span>
                    <svg class="al-spark" viewBox="0 0 80 28" width="80" height="28" fill="none">
                        <polyline points="0,8 14,12 28,10 42,16 56,14 70,20 80,22" stroke="#c0392b" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="al-filter-bar">
            <div class="al-filter-left">
                <div class="al-search-wrap">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.35-4.35" />
                    </svg>
                    <input id="searchInput" class="input" placeholder="Search user, action, route, IP…"
                        value="{{ request('search', '') }}" style="height:34px;font-size:13px;min-width:240px">
                </div>

                <div class="al-tabs" id="methodTabs">
                    <button type="button" class="al-tab {{ !request('method_filter') ? 'active' : '' }}"
                        data-method="">All</button>
                    <button type="button" class="al-tab post {{ request('method_filter') === 'POST' ? 'active' : '' }}"
                        data-method="post">POST</button>
                    <button type="button" class="al-tab put {{ request('method_filter') === 'PUT' ? 'active' : '' }}"
                        data-method="put">PUT</button>
                    <button type="button"
                        class="al-tab delete {{ request('method_filter') === 'DELETE' ? 'active' : '' }}"
                        data-method="delete">DELETE</button>
                </div>
            </div>

            <div class="al-filter-right">
                <input id="dateFilter" class="input" type="date" value="{{ request('date', '') }}"
                    style="height:34px;font-size:13px;min-width:140px">

                <button type="button" class="btn btn--ghost btn--sm" id="applyFiltersBtn">
                    Filters
                </button>

                <button type="button" class="btn btn--ghost btn--sm" id="clearFiltersBtn">
                    ✕ Clear
                </button>
            </div>
        </div>

        <div class="al-feed" id="activityFeed">
            @if ($logs->isEmpty())
                <div class="al-empty">
                    <svg viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor"
                        stroke-width="1.2">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="1" />
                        <path d="M9 12h6M9 16h4" />
                    </svg>
                    <p>No activity logs found.</p>
                </div>
            @else
                @foreach ($logs as $log)
                    @php
                        $rawMethod = strtolower($log->method ?? '');

                        $name = $log->user?->name ?? 'System';
                        $initial = strtoupper(mb_substr($name, 0, 1));
                        $status = data_get($log->response, 'http_status');

                        $actionParts = explode('.', (string) ($log->action ?? ''));
                        $section = $actionParts[0] ?? ($log->route ?? 'system');
                        $verb = $actionParts[1] ?? 'visited';

                        $verbMap = [
                            'index' => 'visited',
                            'create' => 'opened',
                            'store' => 'created',
                            'show' => 'viewed',
                            'edit' => 'edited',
                            'update' => 'updated',
                            'destroy' => 'deleted',
                            'delete' => 'deleted',
                            'dashboard' => 'went to',
                            'login' => 'logged in to',
                            'logout' => 'logged out from',
                            'visit' => 'visited',
                        ];

                        $sectionLabel = \Illuminate\Support\Str::headline(
                            str_replace(['-', '_', '/', '\\'], ' ', (string) $section),
                        );

                        $verbLabel = $verbMap[$verb] ?? 'visited';

                        if (in_array($verb, ['login', 'logout'], true)) {
                            $humanSentence = trim($name . ' ' . $verbLabel);
                        } elseif ($verb === 'dashboard') {
                            $humanSentence = trim($name . ' went to dashboard');
                        } elseif (in_array($verb, ['store', 'create'], true)) {
                            $humanSentence = trim($name . ' created ' . strtolower($sectionLabel));
                        } elseif (in_array($verb, ['update', 'edit'], true)) {
                            $humanSentence = trim($name . ' updated ' . strtolower($sectionLabel));
                        } elseif (in_array($verb, ['destroy', 'delete'], true)) {
                            $humanSentence = trim($name . ' deleted ' . strtolower($sectionLabel));
                        } else {
                            $humanSentence = trim($name . ' ' . $verbLabel . ' ' . strtolower($sectionLabel));
                        }

                        $badgeMethod = match ($verb) {
                            'store', 'create' => 'post',
                            'update', 'edit' => 'put',
                            'destroy', 'delete' => 'delete',
                            default => strtolower($rawMethod),
                        };

                        if (!in_array($badgeMethod, ['post', 'put', 'delete', 'get'], true)) {
                            $badgeMethod = 'get';
                        }

                        $searchBlob = strtolower(
                            implode(
                                ' ',
                                array_filter([
                                    $name,
                                    $log->route,
                                    $log->action,
                                    $log->ip_address,
                                    $log->method,
                                    $humanSentence,
                                ]),
                            ),
                        );
                    @endphp

                    <article class="al-log-card" data-user="{{ strtolower($name) }}"
                        data-route="{{ strtolower($log->route ?? '') }}"
                        data-action="{{ strtolower($log->action ?? '') }}" data-method="{{ $badgeMethod }}"
                        data-date="{{ optional($log->created_at)->format('Y-m-d') }}" data-search="{{ $searchBlob }}">
                        <div class="al-log-left">
                            <div class="al-avatar">
                                @if ($log->user?->avatar)
                                    <img src="{{ asset($log->user->avatar) }}" alt="{{ $name }}">
                                @else
                                    {{ $initial }}
                                @endif
                            </div>

                            <div class="al-log-content">
                                <div class="al-log-sentence">{{ $humanSentence }}</div>
                                <div class="al-log-time">
                                    {{ $log->created_at->diffForHumans() }}
                                    @if ($log->ip_address)
                                        · {{ $log->ip_address }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="al-log-right">
                            <span class="al-method {{ $badgeMethod }}">{{ strtoupper($badgeMethod) }}</span>

                            @if (!empty($log->payload))
                                <button type="button" class="al-btn-icon viewPayload"
                                    data-title="{{ e($humanSentence) }}" data-payload='@json($log->payload)'>
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </article>
                @endforeach
            @endif
        </div>

        <div class="al-client-empty" id="clientEmptyState">
            <svg viewBox="0 0 24 24" width="44" height="44" fill="none" stroke="currentColor"
                stroke-width="1.2">
                <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                <rect x="9" y="3" width="6" height="4" rx="1" />
                <path d="M9 12h6M9 16h4" />
            </svg>
            <strong>No matching logs.</strong>
            <div>Try a different search, method, or date.</div>
        </div>

        @if ($logs->hasPages())
            <div class="pagination">
                <div class="meta">
                    Showing {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} logs
                </div>

                <div class="pager">
                    {{ $logs->appends(request()->query())->onEachSide(1)->links() }}
                </div>
            </div>
        @endif
    </section>

    {{-- MODAL --}}
    <div id="al-modal" aria-hidden="true">
        <div class="al-modal-backdrop" id="al-modal-backdrop"></div>
        <div class="al-modal-box" role="dialog" aria-modal="true" aria-labelledby="al-modal-title">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;gap:12px">
                <h4 id="al-modal-title" style="font-size:14px;font-weight:700;margin:0"></h4>
                <button type="button" id="al-modal-close" class="al-btn-icon">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <pre id="al-modal-body"></pre>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('searchInput');
            const dateInput = document.getElementById('dateFilter');
            const tabs = Array.from(document.querySelectorAll('#methodTabs .al-tab'));
            const cards = Array.from(document.querySelectorAll('.al-log-card'));
            const clientEmptyState = document.getElementById('clientEmptyState');
            const clearBtn = document.getElementById('clearFiltersBtn');
            const clearTopBtn = document.getElementById('clearFiltersTopBtn');
            const applyBtn = document.getElementById('applyFiltersBtn');

            const modal = document.getElementById('al-modal');
            const modalTitle = document.getElementById('al-modal-title');
            const modalBody = document.getElementById('al-modal-body');
            const modalClose = document.getElementById('al-modal-close');
            const modalBackdrop = document.getElementById('al-modal-backdrop');

            let activeMethod = '';

            function normalize(value) {
                return (value || '').toString().trim().toLowerCase();
            }

            function filterCards() {
                const search = normalize(searchInput.value);
                const date = dateInput.value;
                let visibleCount = 0;

                cards.forEach(card => {
                    const rowSearch = normalize(card.dataset.search);
                    const rowMethod = normalize(card.dataset.method);
                    const rowDate = card.dataset.date || '';

                    const matchesSearch = !search || rowSearch.includes(search);
                    const matchesMethod = !activeMethod || rowMethod === activeMethod;
                    const matchesDate = !date || rowDate === date;

                    const visible = matchesSearch && matchesMethod && matchesDate;
                    card.style.display = visible ? '' : 'none';

                    if (visible) visibleCount++;
                });

                if (clientEmptyState) {
                    clientEmptyState.style.display = (cards.length > 0 && visibleCount === 0) ? 'block' : 'none';
                }
            }

            function setActiveTab(method) {
                tabs.forEach(tab => {
                    tab.classList.toggle('active', normalize(tab.dataset.method) === normalize(method));
                });
            }

            function clearFilters() {
                searchInput.value = '';
                dateInput.value = '';
                activeMethod = '';
                setActiveTab('');
                filterCards();
            }

            function openModal(title, payload) {
                modalTitle.textContent = title + ' — Payload';
                modalBody.textContent = payload && Object.keys(payload).length ?
                    JSON.stringify(payload, null, 2) :
                    'No payload data for this request.';

                modal.style.display = 'flex';
                modal.setAttribute('aria-hidden', 'false');
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
                document.body.style.overflow = '';
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    activeMethod = normalize(tab.dataset.method);
                    setActiveTab(activeMethod);
                    filterCards();
                });
            });

            searchInput.addEventListener('input', filterCards);
            dateInput.addEventListener('change', filterCards);
            applyBtn.addEventListener('click', filterCards);
            clearBtn.addEventListener('click', clearFilters);
            clearTopBtn.addEventListener('click', clearFilters);

            document.querySelectorAll('.viewPayload').forEach(btn => {
                btn.addEventListener('click', () => {
                    let payload = {};
                    try {
                        payload = JSON.parse(btn.dataset.payload || '{}');
                    } catch (e) {
                        payload = {};
                    }
                    openModal(btn.dataset.title || 'Activity', payload);
                });
            });

            modalClose.addEventListener('click', closeModal);
            modalBackdrop.addEventListener('click', closeModal);

            document.addEventListener('keydown', e => {
                if (e.key === 'Escape') closeModal();
            });

            filterCards();
        });
    </script>
@endsection
