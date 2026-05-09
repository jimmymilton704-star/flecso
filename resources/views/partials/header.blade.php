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

            </div>
        </div>
        <div class="notify-wrap">
            <button class="icon-btn icon-btn--notify" aria-label="Alerts" id="AlertBtn">
                <svg width="20px" height="20px" viewBox="-2.5 -2.5 30.00 30.00" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round" stroke="#CCCCCC"
                        stroke-width="0.05"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M18.2202 21.25H5.78015C5.14217 21.2775 4.50834 21.1347 3.94373 20.8364C3.37911 20.5381 2.90402 20.095 2.56714 19.5526C2.23026 19.0101 2.04372 18.3877 2.02667 17.7494C2.00963 17.111 2.1627 16.4797 2.47015 15.92L8.69013 5.10999C9.03495 4.54078 9.52077 4.07013 10.1006 3.74347C10.6804 3.41681 11.3346 3.24518 12.0001 3.24518C12.6656 3.24518 13.3199 3.41681 13.8997 3.74347C14.4795 4.07013 14.9654 4.54078 15.3102 5.10999L21.5302 15.92C21.8376 16.4797 21.9907 17.111 21.9736 17.7494C21.9566 18.3877 21.7701 19.0101 21.4332 19.5526C21.0963 20.095 20.6211 20.5381 20.0565 20.8364C19.4919 21.1347 18.8581 21.2775 18.2202 21.25V21.25Z"
                            stroke="#080808" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path
                            d="M10.8809 17.15C10.8809 17.0021 10.9102 16.8556 10.9671 16.7191C11.024 16.5825 11.1074 16.4586 11.2125 16.3545C11.3175 16.2504 11.4422 16.1681 11.5792 16.1124C11.7163 16.0567 11.8629 16.0287 12.0109 16.03C12.2291 16.034 12.4413 16.1021 12.621 16.226C12.8006 16.3499 12.9398 16.5241 13.0211 16.7266C13.1023 16.9292 13.122 17.1512 13.0778 17.3649C13.0335 17.5786 12.9272 17.7745 12.7722 17.9282C12.6172 18.0818 12.4203 18.1863 12.2062 18.2287C11.9921 18.2711 11.7703 18.2494 11.5685 18.1663C11.3666 18.0833 11.1938 17.9426 11.0715 17.7618C10.9492 17.5811 10.8829 17.3683 10.8809 17.15ZM11.2409 14.42L11.1009 9.20001C11.0876 9.07453 11.1008 8.94766 11.1398 8.82764C11.1787 8.70761 11.2424 8.5971 11.3268 8.5033C11.4112 8.40949 11.5144 8.33449 11.6296 8.28314C11.7449 8.2318 11.8697 8.20526 11.9959 8.20526C12.1221 8.20526 12.2469 8.2318 12.3621 8.28314C12.4774 8.33449 12.5805 8.40949 12.6649 8.5033C12.7493 8.5971 12.8131 8.70761 12.852 8.82764C12.8909 8.94766 12.9042 9.07453 12.8909 9.20001L12.7609 14.42C12.7609 14.6215 12.6808 14.8149 12.5383 14.9574C12.3957 15.0999 12.2024 15.18 12.0009 15.18C11.7993 15.18 11.606 15.0999 11.4635 14.9574C11.321 14.8149 11.2409 14.6215 11.2409 14.42Z"
                            fill="#080808"></path>
                    </g>
                </svg>
                <span class="notify-badge" id="AlertBadge">{{ $alerts->count() }}</span>
            </button>
            <div class="notify-pop" id="AlertPop" role="dialog" aria-label="Alerts">
                <header class="notify-pop__head">
                    <div>
                        <h3>Alerts</h3>
                        <p class="muted" id="AlertCount">{{ $alerts->count() }}</p>
                    </div>

                </header>
                <div class="notify-pop__body" id="AlertList">
                    @foreach ($alerts as $alert)
                        <div class="notify-item unread" data-msg-id="{{ $alert->id }}">
                            <img class="notify-item__icon"
                                src="{{ $alert->driver->avatar ?? asset('default-avatar.png') }}" alt=""
                                style="border-radius:50%;object-fit:cover" />
                            <div>
                                <div class="notify-item__title">{{ $alert->driver->full_name ?? 'Unknown Driver' }}
                                </div>
                                <div class="notify-item__sub">{{ $alert->message }}</div>
                            </div>
                            <span class="notify-item__time">{{ $alert->created_at->format('h:i A') }}</span>
                        </div>
                    @endforeach
                </div>
                <footer class="notify-pop__foot"><a href="#" id="AlertMarkAll">Mark all read</a><a href="#"
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
                <img src="{{ $user->avatar ? asset($user->avatar) : 'https://i.pravatar.cc/80?img=47' }}"
                    alt="User" />
                <div class="profile-meta"><span class="profile-name">Marco Bianchi</span><span
                        class="profile-role">Admin
                        · Milan HQ</span></div>
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="m6 9 6 6 6-6" />
                </svg>
            </button>
            <div class="notify-pop profile-pop" id="profilePop" role="menu">
                <header class="notify-pop__head" style="gap:12px">
                    <img src="{{ $user->avatar ? asset($user->avatar) : 'https://i.pravatar.cc/80?img=47' }}"
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
