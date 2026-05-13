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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}" />

    {{-- SweetAlert2 --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* ── OTP input boxes inside Swal ───────────────────────────── */
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

        /* Swal custom button */
        .swal2-confirm.swal-btn-orange {
            background: #FF6B1A !important;
            border-radius: 10px !important;
            font-family: 'Space Grotesk', sans-serif !important;
            font-weight: 600 !important;
            font-size: 15px !important;
            padding: 12px 32px !important;
            box-shadow: 0 4px 14px rgba(255,107,26,.35) !important;
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

        /* countdown timer ring */
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

        /* sendOtp button loading state */
        #sendOtp.loading .label::after {
            content: '';
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid rgba(255,255,255,.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin .7s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        #sendOtp:disabled { opacity: .65; cursor: not-allowed; }
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
            <span class="auth-hero__eyebrow"><span class="dot"></span> Free 14-day trial · No credit card</span>
            <h1>Start delivering <em>smarter</em> today</h1>
            <p class="auth-hero__sub">Join 2,800+ logistics teams that run their fleet on Flecso — from 10-truck
                family businesses to cross-European operators moving 12,000 containers a month.</p>

            <div class="auth-features">
                <div class="auth-feature">
                    <div class="auth-feature__icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </div>
                    <div>
                        <h4>Set up in under 10 minutes</h4>
                        <p>Import your fleet via CSV or connect existing TMS.</p>
                    </div>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature__icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </div>
                    <div>
                        <h4>Unlimited users on every plan</h4>
                        <p>Invite your whole team — no per-seat fees, ever.</p>
                    </div>
                </div>
                <div class="auth-feature">
                    <div class="auth-feature__icon">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                    </div>
                    <div>
                        <h4>ISO 6346 · EORI · Tachograph ready</h4>
                        <p>Built for European compliance from day one.</p>
                    </div>
                </div>
            </div>

            <div class="auth-testimonial">
                <p>"We replaced three different tools and one spreadsheet with Flecso. My dispatchers actually enjoy their shift now."</p>
                <div class="auth-testimonial__author">
                    <img src="https://i.pravatar.cc/80?img=68" alt="">
                    <div><strong>Rafał Kowalczyk</strong><span>CEO · Baltic Rail Logistics</span></div>
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
                <h2>Create your account</h2>
                <p>Already on Flecso? <a href="{{ route('login') }}">Sign in</a></p>
            </div>

            {{-- SUCCESS --}}
            @if (session('success'))
                <div class="auth-alert auth-alert--success">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="auth-alert auth-alert--error">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form class="auth-form" method="POST" action="{{ url('/register') }}" id="registerForm">
                @csrf

                {{-- Full name --}}
                <div class="auth-field">
                    <label for="name">Full name</label>
                    <div class="auth-input">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-7 8-7s8 3 8 7"/></svg>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required />
                    </div>
                </div>

                {{-- Email --}}
                <div class="auth-field">
                    <label for="email">Work email</label>
                    <div class="auth-input">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 6-10 7L2 6"/></svg>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="you@company.com" required />
                    </div>
                </div>

                {{-- Phone --}}
                <div class="auth-field">
                    <label for="phone">
                        Phone
                        <span style="font-size:11px;color:#a0aec0;font-weight:400;margin-left:4px;">(optional · OTP verification)</span>
                    </label>
                    <div class="auth-input" style="position:relative;">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 3.1 5.18 2 2 0 0 1 5.11 3h3a2 2 0 0 1 2 1.72c.12.86.32 1.7.59 2.5a2 2 0 0 1-.45 2.11L9 10a16 16 0 0 0 6 6l.67-.25a2 2 0 0 1 2.11-.45c.8.27 1.64.47 2.5.59A2 2 0 0 1 22 16.92z"/></svg>
                        <input type="text" id="phone" name="phone" value="{{ old('phone') }}" placeholder="03001234567 or +923001234567" />
                        {{-- verified badge --}}
                        <span id="phoneVerifiedBadge" style="display:none;position:absolute;right:12px;top:50%;transform:translateY(-50%);background:#dcfce7;color:#16a34a;font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;border:1px solid #bbf7d0;">
                            ✓ Verified
                        </span>
                    </div>
                </div>

                {{-- Send OTP button (standalone, outside submit row) --}}
                <div style="margin: -4px 0 14px;">
                    <button type="button" id="sendOtp"
                        style="background:none;border:1.5px solid #FF6B1A;color:#FF6B1A;font-size:13px;font-weight:600;padding:7px 18px;border-radius:8px;cursor:pointer;font-family:'Space Grotesk',sans-serif;transition:background .2s,color .2s;"
                        onmouseover="this.style.background='#fff7f3'"
                        onmouseout="this.style.background='none'">
                        <span class="label">Send OTP</span>
                    </button>
                </div>

                {{-- Password --}}
                <div class="auth-field">
                    <label for="password">Password</label>
                    <div class="auth-input">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" name="password" id="password" placeholder="Min. 8 characters" required />
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div class="auth-field">
                    <label for="password_confirmation">Confirm password</label>
                    <div class="auth-input">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat password" required />
                    </div>
                </div>

                {{-- Hidden firebase token --}}
                <input type="hidden" name="firebase_id_token" id="firebase_id_token">

                {{-- reCAPTCHA (invisible — rendered off-screen) --}}
                <div id="recaptcha-container" style="margin:0;"></div>

                {{-- Submit --}}
                <button type="submit" class="auth-submit" id="submitBtn">
                    <span class="label">Create Account</span>
                </button>

                <label class="checkbox" style="font-size:13px;margin-top:14px">
                    <input type="checkbox" name="agree" required />
                    I agree to the
                    <a href="https://flecso.com/terms-of-service/" style="color:var(--orange-600);font-weight:600;margin:0 3px">Terms</a>
                    and
                    <a href="https://flecso.com/privacy-policy/" style="color:var(--orange-600);font-weight:600;margin-left:3px">Privacy Policy</a>.
                </label>
            </form>
        </div>
    </main>
</div>

<div class="toast" id="toast" aria-live="polite"></div>

{{-- ═══ FIREBASE + OTP LOGIC ═══════════════════════════════════════════════ --}}
<script type="module">
    import { initializeApp }            from "https://www.gstatic.com/firebasejs/11.10.0/firebase-app.js";
    import { getAuth, RecaptchaVerifier, signInWithPhoneNumber }
                                        from "https://www.gstatic.com/firebasejs/11.10.0/firebase-auth.js";

    const firebaseConfig = {
        apiKey:            "AIzaSyAfmsqySwBOGh8LYbsBWrKWiYZRcjn73hU",
        authDomain:        "flecso-98e70.firebaseapp.com",
        databaseURL:       "https://flecso-98e70-default-rtdb.firebaseio.com",
        projectId:         "flecso-98e70",
        storageBucket:     "flecso-98e70.firebasestorage.app",
        messagingSenderId: "204931264819",
        appId:             "1:204931264819:web:a3bb40797a787e24e70e46",
        measurementId:     "G-96GQ4SBYW9"
    };

    const app  = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    let confirmationResult = null;
    let countdownInterval  = null;

    // ── Normalize phone ──────────────────────────────────────────────────────
    function normalizePhone(raw) {
        let p = raw.trim().replace(/\s+/g, '');
        if (!p) return '';
        if (/^0\d/.test(p))          return '+92' + p.slice(1);  // 03xx → +923xx
        if (/^[1-9]\d{9}$/.test(p)) return '+92' + p;           // 3xx (10 digits)
        return p;                                                  // already +xx
    }

    const phoneInput = document.getElementById('phone');

    // Format on blur
    phoneInput.addEventListener('blur', () => {
        const f = normalizePhone(phoneInput.value);
        if (f) phoneInput.value = f;
    });

    // ── reCAPTCHA (invisible) ────────────────────────────────────────────────
    const recaptchaVerifier = new RecaptchaVerifier(auth, 'recaptcha-container', {
        size: 'invisible',
    });
    await recaptchaVerifier.render();

    // ── Build OTP modal HTML ─────────────────────────────────────────────────
    function buildOtpHtml(phone) {
        return `
            <p style="color:#718096;font-size:14px;margin:0 0 6px;">Code sent to</p>
            <div class="swal-phone-badge">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2A19.86 19.86 0 0 1 3.1 5.18 2 2 0 0 1 5.11 3h3a2 2 0 0 1 2 1.72c.12.86.32 1.7.59 2.5a2 2 0 0 1-.45 2.11L9 10a16 16 0 0 0 6 6l.67-.25a2 2 0 0 1 2.11-.45c.8.27 1.64.47 2.5.59A2 2 0 0 1 22 16.92z"/>
                </svg>
                ${phone}
            </div>
            <div class="otp-wrapper">
                <input class="otp-box" id="otp0" maxlength="1" inputmode="numeric" pattern="[0-9]" />
                <input class="otp-box" id="otp1" maxlength="1" inputmode="numeric" pattern="[0-9]" />
                <input class="otp-box" id="otp2" maxlength="1" inputmode="numeric" pattern="[0-9]" />
                <input class="otp-box" id="otp3" maxlength="1" inputmode="numeric" pattern="[0-9]" />
                <input class="otp-box" id="otp4" maxlength="1" inputmode="numeric" pattern="[0-9]" />
                <input class="otp-box" id="otp5" maxlength="1" inputmode="numeric" pattern="[0-9]" />
            </div>
            <div class="otp-timer">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                Code expires in <span id="otpCountdown">02:00</span>
            </div>
            <div class="swal-resend-row">
                Didn't receive it? <a id="resendLink">Resend code</a>
            </div>
        `;
    }

    // ── Countdown timer ──────────────────────────────────────────────────────
    function startCountdown(seconds = 120) {
        clearInterval(countdownInterval);
        const el = document.getElementById('otpCountdown');
        if (!el) return;

        countdownInterval = setInterval(() => {
            if (!document.getElementById('otpCountdown')) {
                clearInterval(countdownInterval);
                return;
            }
            const m = String(Math.floor(seconds / 60)).padStart(2, '0');
            const s = String(seconds % 60).padStart(2, '0');
            document.getElementById('otpCountdown').textContent = `${m}:${s}`;
            if (seconds <= 0) {
                clearInterval(countdownInterval);
                document.getElementById('otpCountdown').textContent = 'Expired';
            }
            seconds--;
        }, 1000);
    }

    // ── Wire up OTP box navigation ───────────────────────────────────────────
    function wireOtpBoxes() {
        const boxes = document.querySelectorAll('.otp-box');

        boxes.forEach((box, i) => {
            box.addEventListener('input', e => {
                const val = e.target.value.replace(/\D/g, '');
                e.target.value = val;
                if (val) {
                    e.target.classList.add('filled');
                    if (i < boxes.length - 1) boxes[i + 1].focus();
                } else {
                    e.target.classList.remove('filled');
                }
            });

            box.addEventListener('keydown', e => {
                if (e.key === 'Backspace' && !box.value && i > 0) {
                    boxes[i - 1].focus();
                }
            });

            // Allow paste of full 6-digit code
            box.addEventListener('paste', e => {
                e.preventDefault();
                const pasted = (e.clipboardData || window.clipboardData)
                    .getData('text').replace(/\D/g, '').slice(0, 6);
                [...pasted].forEach((ch, idx) => {
                    if (boxes[idx]) {
                        boxes[idx].value = ch;
                        boxes[idx].classList.add('filled');
                    }
                });
                if (boxes[pasted.length - 1]) boxes[pasted.length - 1].focus();
            });
        });
    }

    // ── Get OTP value from boxes ─────────────────────────────────────────────
    function getOtpValue() {
        return [...document.querySelectorAll('.otp-box')]
            .map(b => b.value).join('');
    }

    // ── Open OTP modal ───────────────────────────────────────────────────────
    async function openOtpModal(phoneNumber) {
        const result = await Swal.fire({
            title: 'Verify your phone',
            html: buildOtpHtml(phoneNumber),
            showCancelButton: true,
            confirmButtonText: 'Verify & Continue',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'swal-btn-orange',
                cancelButton:  'swal-btn-outline',
            },
            buttonsStyling: false,
            focusConfirm: false,
            allowOutsideClick: false,
            didOpen: () => {
                wireOtpBoxes();
                startCountdown(120);

                // Auto-focus first box
                setTimeout(() => {
                    const first = document.getElementById('otp0');
                    if (first) first.focus();
                }, 100);

                // Resend link
                document.getElementById('resendLink').addEventListener('click', async () => {
                    try {
                        confirmationResult = await signInWithPhoneNumber(auth, phoneNumber, recaptchaVerifier);
                        startCountdown(120);
                        // Reset boxes
                        document.querySelectorAll('.otp-box').forEach(b => {
                            b.value = '';
                            b.classList.remove('filled');
                        });
                        document.getElementById('otp0').focus();
                        Swal.showValidationMessage('✓ New code sent!');
                        setTimeout(() => Swal.resetValidationMessage(), 2000);
                    } catch (err) {
                        Swal.showValidationMessage('Failed to resend: ' + (err.message || 'Try again'));
                    }
                });
            },
            willClose: () => {
                clearInterval(countdownInterval);
            },
            preConfirm: async () => {
                const code = getOtpValue();

                if (code.length !== 6) {
                    Swal.showValidationMessage('Please enter the complete 6-digit code.');
                    return false;
                }

                if (!confirmationResult) {
                    Swal.showValidationMessage('Session expired. Please request a new code.');
                    return false;
                }

                try {
                    const credential = await confirmationResult.confirm(code);
                    const token      = await credential.user.getIdToken(true);
                    return token;
                } catch (err) {
                    Swal.showValidationMessage(
                        err.code === 'auth/invalid-verification-code'
                            ? 'Incorrect code. Please try again.'
                            : (err.message || 'Verification failed.')
                    );
                    return false;
                }
            },
        });

        return result; // { isConfirmed, value: token }
    }

    // ── Send OTP click ───────────────────────────────────────────────────────
    document.getElementById('sendOtp').addEventListener('click', async () => {
        const raw = phoneInput.value.trim();

        if (!raw) {
            Swal.fire({
                icon: 'info',
                title: 'No phone number',
                text: 'Phone is optional. Leave it blank or enter a number to verify.',
                confirmButtonText: 'Got it',
                customClass: { confirmButton: 'swal-btn-orange' },
                buttonsStyling: false,
            });
            return;
        }

        const phoneNumber = normalizePhone(raw);
        phoneInput.value  = phoneNumber;

        // Loading state
        const btn = document.getElementById('sendOtp');
        btn.disabled = true;
        btn.classList.add('loading');
        btn.querySelector('.label').textContent = 'Sending…';

        try {
            confirmationResult = await signInWithPhoneNumber(auth, phoneNumber, recaptchaVerifier);
        } catch (err) {
            btn.disabled = false;
            btn.classList.remove('loading');
            btn.querySelector('.label').textContent = 'Send OTP';

            Swal.fire({
                icon: 'error',
                title: 'Could not send OTP',
                text: err.message || 'Please check the phone number and try again.',
                confirmButtonText: 'OK',
                customClass: { confirmButton: 'swal-btn-orange' },
                buttonsStyling: false,
            });
            return;
        }

        btn.disabled = false;
        btn.classList.remove('loading');
        btn.querySelector('.label').textContent = 'Resend OTP';

        // ── Auto-open OTP modal ──────────────────────────────────────────────
        const modalResult = await openOtpModal(phoneNumber);

        if (modalResult.isConfirmed && modalResult.value) {
            // Token received — mark phone as verified
            document.getElementById('firebase_id_token').value = modalResult.value;
            document.getElementById('phoneVerifiedBadge').style.display = 'inline-flex';
            phoneInput.readOnly = true;
            btn.querySelector('.label').textContent = '✓ Verified';
            btn.disabled = true;
            btn.style.borderColor = '#16a34a';
            btn.style.color = '#16a34a';
        }
    });
</script>

</body>
</html>