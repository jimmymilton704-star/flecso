<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <title>Create account — Flecso</title>
    <meta name="description" content="Create your Flecso logistics management account." />
    <meta name="theme-color" content="#FF6B1A" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/app_icon.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
</head>

<body class="auth">

    <div class="auth-shell">
        <aside class="auth-hero">
            <div class="auth-hero__top">


                <div class="brand-text"><img src="{{ asset('images/logo-white.png') }}" alt="Flecso Logo" width="120px">
                </div>
            </div>

            <div class="auth-hero__body">
                <span class="auth-hero__eyebrow"><span class="dot"></span> Free 14-day trial · No credit card</span>
                <h1>Start delivering <em>smarter</em> today</h1>
                <p class="auth-hero__sub">Join 2,800+ logistics teams that run their fleet on Flecso — from 10-truck
                    family
                    businesses to cross-European operators moving 12,000 containers a month.</p>

                <div class="auth-features">
                    <div class="auth-feature">
                        <div class="auth-feature__icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <div>
                            <h4>Set up in under 10 minutes</h4>
                            <p>Import your fleet via CSV or connect existing TMS.</p>
                        </div>
                    </div>
                    <div class="auth-feature">
                        <div class="auth-feature__icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <div>
                            <h4>Unlimited users on every plan</h4>
                            <p>Invite your whole team — no per-seat fees, ever.</p>
                        </div>
                    </div>
                    <div class="auth-feature">
                        <div class="auth-feature__icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 6 9 17l-5-5" />
                            </svg>
                        </div>
                        <div>
                            <h4>ISO 6346 · EORI · Tachograph ready</h4>
                            <p>Built for European compliance from day one.</p>
                        </div>
                    </div>
                </div>

                <div class="auth-testimonial">
                    <p>"We replaced three different tools and one spreadsheet with Flecso. My dispatchers actually enjoy
                        their
                        shift now."</p>
                    <div class="auth-testimonial__author">
                        <img src="https://i.pravatar.cc/80?img=68" alt="">
                        <div><strong>Rafał Kowalczyk</strong><span>CEO · Baltic Rail Logistics</span></div>
                    </div>
                </div>
            </div>

            <div class="auth-hero__footer">
                <span>© 2026 Flecso Logistics S.p.A.</span>
                <!-- <span><a href="#">Privacy</a> · <a href="#">Terms</a></span> -->
            </div>
        </aside>

        <main class="auth-main">
            <div class="auth-card">
                <div class="auth-card__head">
                    <h2>Create your account</h2>
                    <p>Already on Flecso? <a href="{{ route('login') }}">Sign in</a></p>
                </div>

                {{-- SUCCESS --}}
                @if (session('success'))
                    <div class="auth-alert auth-alert--success">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- ERROR --}}
                @if ($errors->any())
                    <div class="auth-alert auth-alert--error">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                        <div>
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form class="auth-form" method="POST" action="{{ url('/register') }}">
                    @csrf
                    <div class="auth-field">
                        <label for="name">Full name</label>
                        <div class="auth-input">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <circle cx="12" cy="8" r="4" />
                                <path d="M4 21c0-4 4-7 8-7s8 3 8 7" />
                            </svg>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name"
                                required />
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="auth-field">
                        <label for="email">Work email</label>
                        <div class="auth-input">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="2" y="4" width="20" height="16" rx="2" />
                                <path d="m22 6-10 7L2 6" />
                            </svg>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="you@company.com"
                                required />
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="auth-field">
                        <label for="phone">Phone</label>
                        <div class="auth-input">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 3.1 5.18 2 2 0 0 1 5.11 3h3a2 2 0 0 1 2 1.72c.12.86.32 1.7.59 2.5a2 2 0 0 1-.45 2.11L9 10a16 16 0 0 0 6 6l.67-.25a2 2 0 0 1 2.11-.45c.8.27 1.64.47 2.5.59A2 2 0 0 1 22 16.92z" />
                            </svg>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+39 300 1234567"
                                required />
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="auth-field">
                        <label for="password">Password</label>
                        <div class="auth-input with-button">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <input type="password" name="password" placeholder="Create a strong password" required />
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="auth-field">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="auth-input with-button">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M17 11V7a5 5 0 0 0-10 0v4" />
                                <rect x="3" y="11" width="18" height="11" rx="2" />
                            </svg>
                            <input type="password" name="password_confirmation" placeholder="Confirm your password"
                                required />
                        </div>
                    </div>

                    <label class="checkbox" style="font-size:13px;margin-top:4px">
                        <input type="checkbox" id="agree" name="agree" required />
                        I agree to the <a href="https://flecso.com/terms-of-service/"
                            style="color:var(--orange-600);font-weight:600;margin:0 3px">Terms</a> and <a href="https://flecso.com/privacy-policy/"
                            style="color:var(--orange-600);font-weight:600;margin-left:3px">Privacy Policy</a>.
                    </label>

                    <button type="submit" class="auth-submit">
                        <span class="spinner"></span>
                        <span class="label">Create account</span>
                    </button>
                </form>
            </div>
        </main>
    </div>

    <div class="toast" id="toast" aria-live="polite"></div>
</body>

</html>