<header class="topbar">
    <button class="icon-btn topbar__menu" id="menuToggle" aria-label="Menu"><svg viewBox="0 0 24 24" width="20"
            height="20" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M3 6h18M3 12h18M3 18h18" />
        </svg></button>
    <div class="topbar__search">
        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="11" cy="11" r="7" />
            <path d="m20 20-3-3" />
        </svg>
        <input type="text" placeholder="Search trucks, drivers, trips, containers…" />
        <span class="kbd">⌘K</span>
    </div>
    <div class="topbar__actions">
        <button class="chip"><span class="chip-dot"></span><span>All Systems Operational</span></button>

        <!-- Messages -->
        <div class="notify-wrap">
            <button class="icon-btn icon-btn--notify" aria-label="Messages" id="msgBtn">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                <span class="notify-badge" id="msgBadge">2</span>
            </button>
            <div class="notify-pop" id="msgPop" role="dialog" aria-label="Messages">
                <header class="notify-pop__head">
                    <div>
                        <h3>Messages</h3>
                        <p class="muted" id="msgCount">2 unread</p>
                    </div>
                    <button class="btn btn--sm btn--ghost" id="msgCompose"><svg viewBox="0 0 24 24" width="14"
                            height="14" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14" />
                        </svg> New</button>
                </header>
                <div class="notify-pop__body" id="msgList"></div>
                <footer class="notify-pop__foot"><a href="#" id="msgMarkAll">Mark all read</a><a href="#"
                        class="link-primary">Open inbox</a></footer>
            </div>
        </div>

        <!-- Notifications -->
        <!-- Language -->
        <div class="notify-wrap">
            <button class="icon-btn icon-btn--notify" aria-label="Language" id="langBtn">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20" />
                </svg>
            </button>

            <div class="notify-pop" id="langPop" role="menu">

                <header class="notify-pop__head">
                    <div>
                        <h3>Language</h3>
                        <p class="muted">Select your preferred language</p>
                    </div>
                </header>

                <div class="notify-pop__body" style="padding:6px">

                    <button class="profile-link lang-switch" data-lang="en">
                        🇬🇧 <span>English</span>
                    </button>

                    <button class="profile-link lang-switch" data-lang="it">
                        🇮🇹 <span>Italiano</span>
                    </button>

                    <button class="profile-link lang-switch" data-lang="de">
                        🇩🇪 <span>Deutsch</span>
                    </button>

                    <button class="profile-link lang-switch" data-lang="es">
                        🇪🇸 <span>Español</span>
                    </button>

                    <button class="profile-link lang-switch" data-lang="pl">
                        🇵🇱 <span>Polski</span>
                    </button>

                </div>

            </div>
        </div>


        <!-- Profile -->
        <div class="notify-wrap">
            <button class="topbar__profile" id="profileBtn" type="button">
                <img src="https://i.pravatar.cc/80?img=47" alt="User" />
                <div class="profile-meta"><span class="profile-name">Marco Bianchi</span><span
                        class="profile-role">Admin · Milan HQ</span></div>
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>
            <div class="notify-pop profile-pop" id="profilePop" role="menu">
                <header class="notify-pop__head" style="gap:12px">
                    <img src="https://i.pravatar.cc/80?img=47"
                        style="width:44px;height:44px;border-radius:50%;object-fit:cover" alt="">
                    <div style="flex:1">
                        <h3 style="font-size:14px">Marco Bianchi</h3>
                        <p class="muted">marco.b@flecso.io</p>
                    </div>
                </header>
                <div class="notify-pop__body" style="padding:6px">
                    <button class="profile-link" data-action="profile"><svg viewBox="0 0 24 24" width="16"
                            height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="8" r="4" />
                            <path d="M4 21c0-4 4-7 8-7s8 3 8 7" />
                        </svg><span>View profile</span></button>
                    <button class="profile-link" data-action="settings"><svg viewBox="0 0 24 24" width="16"
                            height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3" />
                            <path
                                d="M12 1v4M12 19v4M4.2 4.2l2.9 2.9M16.9 16.9l2.9 2.9M1 12h4M19 12h4M4.2 19.8l2.9-2.9M16.9 7.1l2.9-2.9" />
                        </svg><span>Settings</span></button>
                    <button class="profile-link" data-action="billing"><svg viewBox="0 0 24 24" width="16"
                            height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="6" width="18" height="12" rx="2" />
                            <path d="M3 10h18" />
                        </svg><span>Billing</span></button>
                    <button class="profile-link" data-action="help"><svg viewBox="0 0 24 24" width="16"
                            height="16" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01" />
                        </svg><span>Help & Support</span></button>
                </div>
                <footer class="notify-pop__foot" style="padding:8px">
                    <a class="profile-link--danger" style="width:100%;justify-content:center"
                        href="{{ route('logout') }}"><svg viewBox="0 0 24 24" width="16" height="16"
                            fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" />
                        </svg><span>Sign out</span></a>
                </footer>
            </div>
        </div>
    </div>
</header>
