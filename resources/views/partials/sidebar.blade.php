<aside class="sidebar" id="sidebar">
    <div class="sidebar__brand">
        <div class="brand-mark">
            <svg viewBox="0 0 32 32" width="28" height="28" fill="none">
                <path d="M6 8h20l-4 8h-12l-2 4h16" stroke="url(#g1)" stroke-width="2.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                <circle cx="11" cy="24" r="2.5" stroke="url(#g1)" stroke-width="2.5" />
                <circle cx="22" cy="24" r="2.5" stroke="url(#g1)" stroke-width="2.5" />
                <defs>
                    <linearGradient id="g1" x1="0" y1="0" x2="32" y2="32">
                        <stop offset="0%" stop-color="#FF7A1A" />
                        <stop offset="100%" stop-color="#FF3D00" />
                    </linearGradient>
                </defs>
            </svg>
        </div>
        <div class="brand-text"><span class="brand-name">Flecso</span><span class="brand-tag">Logistics OS</span></div>
    </div>

    <nav class="sidebar__nav" id="sidebarNav">
        <span class="nav-section-label">Overview</span>
        <a href="index.html" class="nav-link active">
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
            <span>SOS Alerts</span><span class="nav-badge nav-badge--danger">3</span>
        </a>
        <a href="{{ route('trucks.index') }}" class="nav-link">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 7h10v10H3z" />
                <path d="M13 10h5l3 3v4h-8" />
                <circle cx="7" cy="18" r="2" />
                <circle cx="17" cy="18" r="2" />
            </svg>
            <span>Trucks</span><span class="nav-badge">128</span>
        </a>
        <a href="{{ route('containers.index') }}" class="nav-link">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="7" width="18" height="11" rx="1.5" />
                <path d="M7 7v11M12 7v11M17 7v11" />
            </svg>
            <span>Containers</span><span class="nav-badge">342</span>
        </a>
        <a href="{{ route('drivers.index') }}" class="nav-link">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="8" r="4" />
                <path d="M4 21c0-4 4-7 8-7s8 3 8 7" />
            </svg>
            <span>Drivers</span><span class="nav-badge">86</span>
        </a>
        <a href="{{ route('trips.index') }}" class="nav-link">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 2v4M5 7l2.5 2.5M19 7l-2.5 2.5M12 22a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
                <path d="m8 13 4-4 4 4-4 4z" />
            </svg>
            <span>Trips</span><span class="nav-dot"></span>
        </a>

        <span class="nav-section-label">System</span>
        <a href="settings.html" class="nav-link">
            <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="3" />
                <path
                    d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9 1.65 1.65 0 0 0 4.27 7.18l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1Z" />
            </svg>
            <span>Settings</span>
        </a>
    </nav>

    <div class="sidebar__upgrade">
        <div class="upgrade-card">
            <div class="upgrade-icon"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="m12 2 2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z" />
                </svg></div>
            <h4>Upgrade to Premium</h4>
            <p>Unlock AI routing, advanced analytics, and priority support.</p>
            <button class="btn btn--primary btn--block">Upgrade Now</button>
        </div>
    </div>
</aside>