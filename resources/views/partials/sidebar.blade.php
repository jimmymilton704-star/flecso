<aside class="sidebar" id="sidebar">
    <div class="sidebar__brand">

        <div class="brand-text"><img src="{{ asset('images/logo.png') }}" alt="Flecso Logo" width="120px"></div>
    </div>

    <nav class="sidebar__nav" id="sidebarNav">
        <span class="nav-section-label">Overview</span>
        <a href="{{ route('dashboard') }}" class="nav-link ">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="9" rx="1.5" />
                <rect x="14" y="3" width="7" height="5" rx="1.5" />
                <rect x="14" y="12" width="7" height="9" rx="1.5" />
                <rect x="3" y="16" width="7" height="5" rx="1.5" />
            </svg>
            <span>Dashboard</span>
        </a>

        <span class="nav-section-label">Operations</span>
        <a href="{{ route('sos.index') }}" class="nav-link">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path
                    d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0ZM12 9v4M12 17h.01" />
            </svg>
            <span>Alerts</span>
            {{-- <span class="nav-badge nav-badge--danger">3</span> --}}
        </a>

        <a href="{{ route('chat.index') }}" class="nav-link">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
            </svg>
            <span>Inbox</span>
        </a>

        <a href="{{ route('trucks.index') }}" class="nav-link">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 7h10v10H3z" />
                <path d="M13 10h5l3 3v4h-8" />
                <circle cx="7" cy="18" r="2" />
                <circle cx="17" cy="18" r="2" />
            </svg>
            <span>Trucks</span>
            {{-- <span class="nav-badge">128</span> --}}
        </a>
        <a href="{{ route('containers.index') }}" class="nav-link">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="7" width="18" height="11" rx="1.5" />
                <path d="M7 7v11M12 7v11M17 7v11" />
            </svg>
            <span>Containers</span>
            {{-- <span class="nav-badge">342</span> --}}
        </a>
        <a href="{{ route('drivers.index') }}" class="nav-link">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="8" r="4" />
                <path d="M4 21c0-4 4-7 8-7s8 3 8 7" />
            </svg>
            <span>Drivers</span>
            {{-- <span class="nav-badge">86</span> --}}
        </a>
        <a href="{{ route('trips.index') }}" class="nav-link">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2v4M5 7l2.5 2.5M19 7l-2.5 2.5M12 22a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
                <path d="m8 13 4-4 4 4-4 4z" />
            </svg>
            <span>Trips</span>
            {{-- <span class="nav-dot"></span> --}}
        </a>

        <span class="nav-section-label">System</span>
        <a href="{{ route('setting') }}" class="nav-link">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="3" />
                <path
                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9 1.65 1.65 0 0 0 4.27 7.18l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z" />
            </svg>
            <span>Settings</span>
        </a>
        <a href="{{ route('activity.logs') }}" class="nav-link">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
            </svg>

            <span>Activity Log</span>
        </a>
        <a href="{{ route('leaderboard') }}" class="nav-link">
            <svg class="nav-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M16.712 4.33a9.027 9.027 0 0 1 1.652 1.306c.51.51.944 1.064 1.306 1.652M16.712 4.33l-3.448 4.138m3.448-4.138a9.014 9.014 0 0 0-9.424 0M19.67 7.288l-4.138 3.448m4.138-3.448a9.014 9.014 0 0 1 0 9.424m-4.138-5.976a3.736 3.736 0 0 0-.88-1.388 3.737 3.737 0 0 0-1.388-.88m2.268 2.268a3.765 3.765 0 0 1 0 2.528m-2.268-4.796a3.765 3.765 0 0 0-2.528 0m4.796 4.796c-.181.506-.475.982-.88 1.388a3.736 3.736 0 0 1-1.388.88m2.268-2.268 4.138 3.448m0 0a9.027 9.027 0 0 1-1.306 1.652c-.51.51-1.064.944-1.652 1.306m0 0-3.448-4.138m3.448 4.138a9.014 9.014 0 0 1-9.424 0m5.976-4.138a3.765 3.765 0 0 1-2.528 0m0 0a3.736 3.736 0 0 1-1.388-.88 3.737 3.737 0 0 1-.88-1.388m2.268 2.268L7.288 19.67m0 0a9.024 9.024 0 0 1-1.652-1.306 9.027 9.027 0 0 1-1.306-1.652m0 0 4.138-3.448M4.33 16.712a9.014 9.014 0 0 1 0-9.424m4.138 5.976a3.765 3.765 0 0 1 0-2.528m0 0c.181-.506.475-.982.88-1.388a3.736 3.736 0 0 1 1.388-.88m-2.268 2.268L4.33 7.288m6.406 1.18L7.288 4.33m0 0a9.024 9.024 0 0 0-1.652 1.306A9.025 9.025 0 0 0 4.33 7.288" />
            </svg>

            <span>Leaderboard</span>
        </a>
    </nav>

    <div class="sidebar__upgrade">
        <div class="upgrade-card">
            <div class="upgrade-icon"><svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="m12 2 2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z" />
                </svg></div>
            <h4>Upgrade to Premium</h4>
            <p>Unlock AI routing, advanced analytics, and priority support.</p>
            <button class="btn btn--primary btn--block">Upgrade Now</button>
        </div>
    </div>
</aside>
