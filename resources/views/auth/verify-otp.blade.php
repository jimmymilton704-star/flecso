<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <title>Verify OTP — Flecso</title>

    <meta name="description" content="Verify your email OTP for Flecso account." />
    <meta name="theme-color" content="#FF6B1A" />

    <link rel="icon" type="image/svg+xml" href="{{ asset('images/app_icon.png') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />
</head>

<body class="auth">

<div class="auth-shell">

    <!-- LEFT HERO -->
    <aside class="auth-hero">

        <div class="auth-hero__top">
            <div class="brand-text">
                <img src="{{ asset('images/logo-white.png') }}" alt="Flecso Logo" width="120px">
            </div>
        </div>

        <div class="auth-hero__body">

            <span class="auth-hero__eyebrow"><span class="dot"></span> Secure Verification</span>

            <h1>Verify your <em>email</em></h1>

            <p class="auth-hero__sub">
                We’ve sent a 6-digit OTP to your email. Enter it below to activate your account.
            </p>

            <div class="auth-features">

                <div class="auth-feature">
                    <div class="auth-feature__icon">🔒</div>
                    <div>
                        <h4>Secure Access</h4>
                        <p>Your account is protected with OTP verification.</p>
                    </div>
                </div>

                <div class="auth-feature">
                    <div class="auth-feature__icon">⏱</div>
                    <div>
                        <h4>Expires in 10 min</h4>
                        <p>OTP automatically expires for security.</p>
                    </div>
                </div>

            </div>

        </div>

        <div class="auth-hero__footer">
            <span>© 2026 Flecso Logistics S.p.A.</span>
        </div>

    </aside>

    <!-- RIGHT FORM -->
    <main class="auth-main">

        <div class="auth-card">

            <div class="auth-card__head">
                <h2>Verify OTP</h2>
                <p>Enter the 6-digit code sent to your email</p>
            </div>

            {{-- ERRORS --}}
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- SUCCESS --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form class="auth-form" method="POST" action="{{ route('otp.verify') }}">
                @csrf

                <!-- OTP INPUT -->
                <div class="auth-field">
                    <label>OTP Code</label>

                    <div class="auth-input">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"/>
                        </svg>

                        <input type="text"
                               name="otp"
                               maxlength="6"
                               placeholder="Enter 6-digit OTP"
                               required>
                    </div>

                    <span class="auth-field__hint">Check your email inbox or spam folder</span>
                </div>

                <!-- HIDDEN EMAIL -->
               <input type="hidden" name="email" value="{{ session('otp_email') }}">

                <!-- SUBMIT -->
                <button type="submit" class="auth-submit">
                    <span class="spinner"></span>
                    <span class="label">Verify OTP</span>
                </button>

            </form>

            <!-- RESEND -->
            <div style="margin-top:15px;text-align:center;font-size:13px;">
                Didn’t receive code?

                <form method="POST" action="{{ route('otp.resend') }}" style="display:inline;">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">

                    <button type="submit" style="background:none;border:none;color:#FF6B1A;cursor:pointer;font-weight:600;">
                        Resend OTP
                    </button>
                </form>
            </div>

        </div>

    </main>

</div>

<div class="toast" id="toast" aria-live="polite"></div>

</body>
</html>