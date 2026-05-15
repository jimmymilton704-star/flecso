<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <title>Login — Flecso</title>
    <meta name="description" content="Login to your Flecso logistics management account." />
    <meta name="theme-color" content="#FF6B1A" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/app_icon.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

    {{-- Country picker CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/css/intlTelInput.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* ── Login tabs ─────────────────────────────────────────────────── */
        .login-tabs {
            display: flex;
            gap: 0;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 24px;
        }

        .login-tab {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 11px 0;
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #a0aec0;
            background: #f8fafc;
            border: none;
            cursor: pointer;
            transition: background .2s, color .2s;
        }

        .login-tab:first-child {
            border-right: 1.5px solid #e2e8f0;
        }

        .login-tab.active {
            background: #fff;
            color: #FF6B1A;
        }

        .login-tab svg {
            flex-shrink: 0;
        }

        /* ── Tab panels ─────────────────────────────────────────────────── */
        .tab-panel {
            display: none;
        }

        .tab-panel.active {
            display: block;
        }

        /* ── Divider ────────────────────────────────────────────────────── */
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 6px 0 18px;
            color: #cbd5e0;
            font-size: 12px;
            font-weight: 500;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        /* ── OTP boxes ───────────────────────────────── */
        .otp-wrapper {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin: 18px 0 6px;
        }

        .otp-box {
            width: 48px;
            height: 56px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 22px;
            font-weight: 700;
            text-align: center;
            color: #1a202c;
            background: #f8fafc;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
            font-family: 'Space Grotesk', sans-serif;
        }

        .otp-box:focus {
            border-color: #FF6B1A;
            box-shadow: 0 0 0 3px rgba(255, 107, 26, .15);
            background: #fff;
        }

        .otp-box.filled {
            border-color: #FF6B1A;
            background: #fff7f3;
        }

        .swal-phone-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff7f3;
            border: 1px solid #ffd8bf;
            color: #c05000;
            font-size: 13px;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 20px;
            margin: 4px 0 2px;
            font-family: 'Space Grotesk', sans-serif;
        }

        .swal-resend-row {
            margin-top: 14px;
            font-size: 13px;
            color: #718096;
        }

        .swal-resend-row a {
            color: #FF6B1A;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
        }

        .swal-resend-row a:hover {
            text-decoration: underline;
        }

        .swal2-confirm.swal-btn-orange {
            background: #FF6B1A !important;
            border-radius: 10px !important;
            font-family: 'Space Grotesk', sans-serif !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            padding: 12px 32px !important;
            box-shadow: 0 4px 14px rgba(255, 107, 26, .35) !important;
        }

        .swal2-cancel.swal-btn-outline {
            background: transparent !important;
            border: 1.5px solid #e2e8f0 !important;
            color: #4a5568 !important;
            border-radius: 10px !important;
            font-family: 'Space Grotesk', sans-serif !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            padding: 12px 24px !important;
        }

        .otp-timer {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            color: #a0aec0;
            margin-top: 4px;
        }

        .otp-timer span {
            font-weight: 700;
            color: #FF6B1A;
            min-width: 24px;
            display: inline-block;
        }

        /* ── Send OTP button ────────────────────────────────────────────── */
        #sendLoginOtp {
            width: 100%;
            padding: 13px;
            background: none;
            border: 1.5px solid #FF6B1A;
            color: #FF6B1A;
            font-size: 14px;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            font-family: 'Space Grotesk', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background .2s, color .2s;
            margin-top: 4px;
        }

        #sendLoginOtp:hover {
            background: #fff7f3;
        }

        #sendLoginOtp:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        #sendLoginOtp.loading .btn-label::after {
            content: '';
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255, 107, 26, .3);
            border-top-color: #FF6B1A;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        /* ── Alert flash ────────────────────────────────────────────────── */
        .auth-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13.5px;
            margin-bottom: 16px;
        }

        .auth-alert--error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .auth-alert--success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #15803d;
        }

        /* ── Country picker phone input ─────────────────────────────────── */
        .phone-country-field {
            width: 100%;
            position: relative;
        }

        .phone-country-field .iti {
            width: 100%;
            display: block;
        }

        .phone-country-field input {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            background: transparent;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: #1a202c;
            padding-top: 0;
            padding-bottom: 0;
        }

        .phone-country-field input::placeholder {
            color: #a0aec0;
        }

        .phone-country-field .iti__selected-country {
            padding-left: 8px;
        }

        .phone-country-field .iti__country-container {
            border-radius: 10px 0 0 10px;
        }

        .phone-country-field .iti__dropdown-content {
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 18px 45px rgba(15, 23, 42, 0.16);
            z-index: 99999;
        }

        .phone-country-field .iti__search-input {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            margin: 8px;
            width: calc(100% - 16px);
        }

        .phone-country-field .iti__country {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            padding: 8px 10px;
        }

        .phone-country-field .iti__dial-code {
            color: #718096;
        }

        .auth-input.phone-auth-input {
            padding-left: 0;
            overflow: visible;
        }

        .auth-input.phone-auth-input>svg {
            margin-left: 14px;
            flex-shrink: 0;
        }

        .phone-country-field #loginPhone.iti__tel-input {
            padding-left: 87px !important;
        }
    </style>
</head>

<body class="auth">

    <div class="auth-shell">

        {{-- ═══ LEFT HERO ═══════════════════════════════════════════════════════ --}}
        <aside class="auth-hero">
            <div class="auth-hero__top">
                <div class="brand-text">
                    <img src="{{ asset('images/logo-white.png') }}" alt="Flecso Logo" width="120px">
                </div>
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
            </div>
        </aside>

        {{-- ═══ RIGHT FORM ════════════════════════════════════════════════════════ --}}
        <main class="auth-main">
            <div class="auth-card">
                <div class="auth-card__head">
                    <h2>Sign in</h2>
                    <p>New to Flecso? <a href="{{ url('/register') }}">Create an account</a></p>
                </div>

                {{-- SUCCESS --}}
                @if (session('success'))
                    <div class="auth-alert auth-alert--success">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 6 9 17l-5-5" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                {{-- ERROR --}}
                @if (session('error') || $errors->any())
                    <div class="auth-alert auth-alert--error">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M18 6 6 18M6 6l12 12" />
                        </svg>
                        <div>
                            @if (session('error'))
                                <div>{{ session('error') }}</div>
                            @endif

                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- ── TABS ───────────────────────────────────────────────── --}}
                <div class="login-tabs">
                    <button class="login-tab active" id="tabEmail" type="button" onclick="switchTab('email')">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                            <path d="m22 6-10 7L2 6" />
                        </svg>
                        Email & Password
                    </button>

                    <button class="login-tab" id="tabPhone" type="button" onclick="switchTab('phone')">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path
                                d="M22 16.92v3a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 3.1 5.18 2 2 0 0 1 5.11 3h3a2 2 0 0 1 2 1.72c.12.86.32 1.7.59 2.5a2 2 0 0 1-.45 2.11L9 10a16 16 0 0 0 6 6l.67-.25a2 2 0 0 1 2.11-.45c.8.27 1.64.47 2.5.59A2 2 0 0 1 22 16.92z" />
                        </svg>
                        Phone OTP
                    </button>
                </div>

                {{-- ── TAB 1 : EMAIL + PASSWORD ───────────────────────────── --}}
                <div class="tab-panel active" id="panelEmail">
                    <form class="auth-form" method="POST" action="{{ url('/login') }}">
                        @csrf

                        <div class="auth-field">
                            <label for="email">Email</label>
                            <div class="auth-input">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="4" width="20" height="16" rx="2" />
                                    <path d="m22 6-10 7L2 6" />
                                </svg>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    autocomplete="email" placeholder="you@company.com" required />
                            </div>
                        </div>

                        <div class="auth-field">
                            <label for="emailPassword">Password</label>
                            <div class="auth-input with-button">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                                <input type="password" id="emailPassword" name="password"
                                    autocomplete="current-password" placeholder="Enter your password" required />
                                <button type="button" class="auth-input__btn"
                                    onclick="togglePw('emailPassword', this)">👁</button>
                            </div>
                        </div>

                        <div class="auth-row">
                            <label class="checkbox">
                                <input type="checkbox" name="remember"> Remember me for 30 days
                            </label>
                            <a href="{{ url('/forgot-password') }}">Forgot password?</a>
                        </div>

                        <button type="submit" class="auth-submit">
                            <span class="spinner"></span>
                            <span class="label">Sign in</span>
                        </button>
                    </form>
                </div>

                {{-- ── TAB 2 : PHONE + PASSWORD ───────────────────────────── --}}
                <div class="tab-panel" id="panelPhone">
                    <form class="auth-form" method="POST" action="{{ url('/login/phone') }}" id="phoneLoginForm">
                        @csrf

                        <div class="auth-field">
                            <label for="loginPhone">Phone number</label>

                            <div class="auth-input phone-auth-input">
                                {{-- <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 3.1 5.18 2 2 0 0 1 5.11 3h3a2 2 0 0 1 2 1.72c.12.86.32 1.7.59 2.5a2 2 0 0 1-.45 2.11L9 10a16 16 0 0 0 6 6l.67-.25a2 2 0 0 1 2.11-.45c.8.27 1.64.47 2.5.59A2 2 0 0 1 22 16.92z" />
                                </svg> --}}

                                <div class="phone-country-field">
                                    <input type="tel" id="loginPhone" name="phone"
                                        value="{{ old('phone') }}" autocomplete="tel" required />
                                </div>
                            </div>
                        </div>

                        <div class="auth-field">
                            <label for="phonePassword">Password</label>
                            <div class="auth-input with-button">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="11" width="18" height="11" rx="2" />
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                </svg>
                                <input type="password" id="phonePassword" name="password"
                                    placeholder="Enter your password" autocomplete="current-password" required />
                                <button type="button" class="auth-input__btn"
                                    onclick="togglePw('phonePassword', this)">👁</button>
                            </div>
                        </div>

                        <div class="auth-row">
                            <label class="checkbox">
                                <input type="checkbox" name="remember"> Remember me for 30 days
                            </label>
                            <a href="{{ url('/forgot-password') }}">Forgot password?</a>
                        </div>

                        <button type="submit" class="auth-submit">
                            <span class="spinner"></span>
                            <span class="label">Sign in</span>
                        </button>
                    </form>
                </div>

            </div>
        </main>
    </div>

    <div class="toast" id="toast" aria-live="polite"></div>

    {{-- Country picker JS --}}
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js"></script>

    {{-- ═══ SCRIPTS ════════════════════════════════════════════════════════════ --}}
    <script>
        let loginPhoneIti = null;

        function switchTab(tab) {
            document.getElementById('panelEmail').classList.toggle('active', tab === 'email');
            document.getElementById('panelPhone').classList.toggle('active', tab === 'phone');
            document.getElementById('tabEmail').classList.toggle('active', tab === 'email');
            document.getElementById('tabPhone').classList.toggle('active', tab === 'phone');
        }

        function togglePw(inputId) {
            const inp = document.getElementById(inputId);

            if (!inp) {
                return;
            }

            inp.type = inp.type === 'password' ? 'text' : 'password';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.querySelector('#loginPhone');
            const phoneForm = document.querySelector('#phoneLoginForm');

            if (phoneInput && window.intlTelInput) {
                loginPhoneIti = window.intlTelInput(phoneInput, {
                    initialCountry: 'pk',
                    separateDialCode: true,
                    nationalMode: false,
                    formatOnDisplay: true,
                    autoPlaceholder: 'aggressive',
                    preferredCountries: ['pk', 'ae', 'sa', 'gb', 'us'],
                    utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@25.3.1/build/js/utils.js',
                });
            }

            if (phoneForm && phoneInput) {
                phoneForm.addEventListener('submit', function(e) {
                    if (!loginPhoneIti) {
                        return true;
                    }

                    let rawPhone = phoneInput.value.trim().replace(/\s+/g, '');

                    const selectedCountry = loginPhoneIti.getSelectedCountryData();
                    const dialCode = selectedCountry.dialCode;

                    if (rawPhone.startsWith('0')) {
                        rawPhone = rawPhone.substring(1);
                    }

                    let fullPhone = rawPhone.startsWith('+') ?
                        rawPhone :
                        '+' + dialCode + rawPhone;

                    phoneInput.value = fullPhone;

                    if (!/^\+[1-9]\d{7,14}$/.test(fullPhone)) {
                        e.preventDefault();

                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid phone number',
                            text: 'Please enter a valid phone number.',
                            confirmButtonColor: '#FF6B1A'
                        });

                        return false;
                    }

                    return true;
                });
            }
        });
    </script>

</body>

</html>
