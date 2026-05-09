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
                <div class="brand-mark">
                    <svg viewBox="0 0 32 32" width="28" height="28" fill="none">
                        <path d="M6 8h20l-4 8h-12l-2 4h16" stroke="url(#g3)" stroke-width="2.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <circle cx="11" cy="24" r="2.5" stroke="url(#g3)" stroke-width="2.5" />
                        <circle cx="22" cy="24" r="2.5" stroke="url(#g3)" stroke-width="2.5" />
                        <defs>
                            <linearGradient id="g3" x1="0" y1="0" x2="32" y2="32">
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
                <span class="auth-hero__eyebrow"><span class="dot"></span> Account recovery</span>
                <h1>Forgot your <em>password</em>?</h1>
                <p class="auth-hero__sub">No worries — it happens. Enter the email tied to your Flecso account and we'll
                    send
                    you a secure link to create a new one.</p>

                <div class="auth-features">
                    <div class="auth-feature">
                        <div class="auth-feature__icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2" />
                                <path d="m22 6-10 7L2 6" />
                            </svg>
                        </div>
                        <div>
                            <h4>Reset link arrives in seconds</h4>
                            <p>Check your inbox — and your spam folder just in case.</p>
                        </div>
                    </div>
                    <div class="auth-feature">
                        <div class="auth-feature__icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M12 6v6l4 2" />
                            </svg>
                        </div>
                        <div>
                            <h4>Link expires in 15 minutes</h4>
                            <p>Kept short for your security. Request another anytime.</p>
                        </div>
                    </div>
                    <div class="auth-feature">
                        <div class="auth-feature__icon">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92Z" />
                            </svg>
                        </div>
                        <div>
                            <h4>Stuck? We're human, not a bot</h4>
                            <p>Reach support at <a href="mailto:support@flecso.io"
                                    style="color:#fff">support@flecso.io</a>.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="auth-hero__footer">
                <span>© 2026 Flecso Logistics S.p.A.</span>
                <span><a href="#">Privacy</a> · <a href="#">Terms</a></span>
            </div>
        </aside>

        <main class="auth-main">
            <div class="auth-card">

                <!-- Back -->
                <a href="{{ route('login') }}" class="auth-back">
                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                    Back to sign in
                </a>

                <div class="auth-card__head">
                    <h2>Set new password</h2>
                    <p>Create a strong password for your account.</p>
                </div>

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

                <!-- FORM -->
                <form class="auth-form" method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <!-- Hidden token -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div class="auth-field">
                        <label for="email">Email address</label>
                        <div class="auth-input">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="2" y="4" width="20" height="16" rx="2" />
                                <path d="m22 6-10 7L2 6" />
                            </svg>
                            <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}"
                                placeholder="you@company.com" required />
                        </div>
                    </div>

                    <!-- New Password -->
                    <div class="auth-field">
                        <label for="password">New password</label>
                        <div class="auth-input">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" />
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                            </svg>
                            <input id="password" type="password" name="password" placeholder="Enter new password"
                                required />
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="auth-field">
                        <label for="password_confirmation">Confirm password</label>
                        <div class="auth-input">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                <rect x="3" y="11" width="18" height="11" rx="2" />
                            </svg>
                            <input id="password_confirmation" type="password" name="password_confirmation"
                                placeholder="Confirm new password" required />
                        </div>
                    </div>

                    <button type="submit" class="auth-submit">
                        <span class="spinner"></span>
                        <span class="label">Reset password</span>
                    </button>
                </form>

                <div class="auth-card__foot">
                    Remembered it? <a href="{{ route('login') }}">Sign in instead</a>
                </div>

            </div>
        </main>
    </div>

    <div class="toast" id="toast" aria-live="polite"></div>
</body>

</html>