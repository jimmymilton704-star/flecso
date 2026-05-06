<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <title>Login — Flecso</title>
    <meta name="description" content="Login to your Flecso logistics management account." />
    <meta name="theme-color" content="#FF6B1A" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}" />
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
        <!-- Left: Brand hero -->
        <aside class="auth-hero">
            <div class="auth-hero__top">
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
                <div class="brand-text"><span class="brand-name">Flecso</span><span class="brand-tag">Logistics
                        OS</span></div>
            </div>

            <div class="auth-hero__body">
                <span class="auth-hero__eyebrow"><span class="dot"></span> Trusted by 2,800+ fleets</span>
                <h1>Welcome back to <em>Flecso</em></h1>
                <p class="auth-hero__sub">Sign in to keep every truck, driver, and container moving in perfect sync — on
                    one unified platform.</p>

                <div class="auth-features">
                    <div class="auth-feature">
                        <div class="auth-feature__icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 7h10v10H3z" />
                                <path d="M13 10h5l3 3v4h-8" />
                                <circle cx="7" cy="18" r="2" />
                                <circle cx="17" cy="18" r="2" />
                            </svg>
                        </div>
                        <div>
                            <h4>Live fleet tracking</h4>
                            <p>Every truck on one map, updated every 15 seconds.</p>
                        </div>
                    </div>
                    <div class="auth-feature">
                        <div class="auth-feature__icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="m8 13 4-4 4 4-4 4z" />
                                <path d="M12 22a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
                            </svg>
                        </div>
                        <div>
                            <h4>AI-powered ETAs</h4>
                            <p>Routes that beat traffic before it happens.</p>
                        </div>
                    </div>
                    <div class="auth-feature">
                        <div class="auth-feature__icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" />
                            </svg>
                        </div>
                        <div>
                            <h4>Enterprise-grade security</h4>
                            <p>SOC 2 Type II · GDPR · ISO 27001 compliant.</p>
                        </div>
                    </div>
                </div>

                <div class="auth-testimonial">
                    <p>"We cut dispatch time by 38% in the first month. Flecso just gets how European logistics actually
                        works."</p>
                    <div class="auth-testimonial__author">
                        <img src="https://i.pravatar.cc/80?img=11" alt="">
                        <div><strong>Chiara Moretti</strong><span>Operations Director · Lombardia Freight</span></div>
                    </div>
                </div>
            </div>

            <div class="auth-hero__footer">
                <span>© 2026 Flecso Logistics S.p.A.</span>
                <span><a href="#">Privacy</a> · <a href="#">Terms</a></span>
            </div>
        </aside>

        <!-- Right: Form -->
        <main class="auth-main">
            <div class="auth-card">
                <div class="auth-card__head">
                    <h2>Sign in</h2>
                    <p>New to Flecso? <a href="{{ url('/register') }}">Create an account</a></p>
                </div>

                <form class="auth-form" method="POST" action="{{ url('/login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="auth-field">
                        <label for="email">Email</label>
                        <div class="auth-input">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="2" y="4" width="20" height="16" rx="2" />
                                <path d="m22 6-10 7L2 6" />
                            </svg>

                            <input type="email" name="email" value="{{ old('email') }}" autocomplete="email"
                                placeholder="marco.b@flecso.io" required />
                        </div>
                        <span class="auth-field__hint">We'll never share your email.</span>
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

                            <input type="password" name="password" autocomplete="current-password"
                                placeholder="Enter your password" required />

                            <button type="button" class="auth-input__btn" data-toggle-pw
                            onclick="this.previousElementSibling.type = this.previousElementSibling.type === 'password' ? 'text' : 'password'">
                                👁
                            </button>
                        </div>
                        <span class="auth-field__hint">At least 8 characters.</span>
                    </div>

                    <!-- Remember + Forgot -->
                    <div class="auth-row">
                        <label class="checkbox">
                            <input type="checkbox" name="remember">
                            Remember me for 30 days
                        </label>

                        <a href="{{ url('/forgot-password') }}">Forgot password?</a>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="auth-submit">
                        <span class="spinner"></span>
                        <span class="label">Sign in</span>
                    </button>
                </form>
            </div>
        </main>
    </div>

    <div class="toast" id="toast" aria-live="polite"></div>
</body>

</html>