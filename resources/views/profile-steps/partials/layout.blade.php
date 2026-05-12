{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Profile - Flecso</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/app_icon.png') }}">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/onboarding.css') }}">

    <style>
        .onb-alert {
            display: flex;
            gap: 12px;
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-family: Inter, sans-serif;
            font-size: 14px;
        }

        .onb-alert__icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .onb-alert--danger {
            background: #fff1f1;
            border: 1px solid #ffcccc;
            color: #b30000;
        }

        .onb-alert--danger .onb-alert__icon {
            background: #ffdddd;
            color: #b30000;
        }

        .onb-alert--success {
            background: #f0fff4;
            border: 1px solid #b7f5c1;
            color: #1a7f37;
        }

        .onb-alert--success .onb-alert__icon {
            background: #d4f8dc;
            color: #1a7f37;
        }

        .onb-alert ul {
            margin: 6px 0 0;
            padding-left: 18px;
        }
    </style>

</head>

<body>

    <div class="onb">

        <header class="onb-topbar">
            <div class="onb-topbar__brand">
                <div class="brand-mark">
                    <div class="brand-text"><img src="{{ asset('images/logo.png') }}" alt="Flecso Logo" width="120px">
                    </div>
                </div>
                <strong>Flecso</strong>
            </div>
            <a class="onb-exit" id="onbExit" type="button" href="{{ route('logout') }}">
                Save &amp; exit
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"></path>
                </svg>
            </a>
        </header>
        <nav class="onb-stepper">

            <div class="onb-step {{ $step >= 1 ? ($step == 1 ? 'active' : 'done') : '' }}">
                <div class="onb-step__num">1</div>
                <div>
                    <small>Step 1</small>
                    <div>Company info</div>
                </div>
            </div>

            <div class="onb-step {{ $step >= 2 ? ($step == 2 ? 'active' : 'done') : '' }}">
                <div class="onb-step__num">2</div>
                <div>
                    <small>Step 2</small>
                    <div>Address & comms</div>
                </div>
            </div>

            <div class="onb-step {{ $step >= 3 ? ($step == 3 ? 'active' : 'done') : '' }}">
                <div class="onb-step__num">3</div>
                <div>
                    <small>Step 3</small>
                    <div>Fleet & operations</div>
                </div>
            </div>

            <div class="onb-step {{ $step >= 4 ? ($step == 4 ? 'active' : 'done') : '' }}">
                <div class="onb-step__num">4</div>
                <div>
                    <small>Step 4</small>
                    <div>Representative</div>
                </div>
            </div>

        </nav>

        <div class="onb-shell">

            <aside class="onb-side">
                <div class="onb-side__inner">
                    <span class="onb-side__eyebrow"><span class="dot"></span> Almost there</span>

                    <!-- Step-specific copy -->
                    <div class="onb-side-pane active" data-step="1">
                        <h2>Tell us about your <em>business</em></h2>
                        <p class="onb-side__sub">We need a few legal details to issue invoices, run KYC, and stay fully
                            compliant with Italian regulations.</p>
                        <div class="onb-tips">
                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4>Required for compliance</h4>
                                    <p>Italian fiscal authorities require a verified VAT and fiscal code on file.</p>
                                </div>
                            </div>
                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="m22 6-10 7L2 6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4>Used on invoices</h4>
                                    <p>The legal name appears on every BoL and invoice we generate for you.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="onb-side-pane" data-step="2">
                        <h2>Where can we <em>reach you?</em></h2>
                        <p class="onb-side__sub">Italian e-invoicing requires a PEC inbox and an SDI code. We'll route
                            every electronic invoice through them.</p>
                        <div class="onb-tips">
                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s7-7 7-12a7 7 0 1 0-14 0c0 5 7 12 7 12Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <h4>Registered office only</h4>
                                    <p>This is the legal address on file — separate from your operational depots.</p>
                                </div>
                            </div>
                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="m22 6-10 7L2 6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4>PEC for legal email</h4>
                                    <p>Posta Elettronica Certificata — your certified Italian e-mail address.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="onb-side-pane" data-step="3">
                        <h2>Your <em>fleet</em>, your <em>licences</em></h2>
                        <p class="onb-side__sub">Tell us how big your operation is. This calibrates dashboards, billing
                            tier, and which features we surface first.</p>
                        <div class="onb-tips">
                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 7h10v10H3z"></path>
                                        <path d="M13 10h5l3 3v4h-8"></path>
                                        <circle cx="7" cy="18" r="2"></circle>
                                        <circle cx="17" cy="18" r="2"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <h4>REN required for hauliers</h4>
                                    <p>Your Registro Elettronico Nazionale number unlocks freight features.</p>
                                </div>
                            </div>
                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4>EU licence is optional</h4>
                                    <p>Only needed if you operate cross-border within the European Union.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="onb-side-pane" data-step="4">
                        <h2>Verify the <em>legal representative</em></h2>
                        <p class="onb-side__sub">A signed identity document of the company's legal representative —
                            required for KYC under Italian and EU AML rules.</p>
                        <div class="onb-tips">
                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4>Stored encrypted</h4>
                                    <p>Documents are encrypted at rest and only accessible to compliance staff.</p>
                                </div>
                            </div>
                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h4>JPG, PNG or PDF · max 5 MB</h4>
                                    <p>A clear scan or photograph of an official identity document is enough.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="onb-help">
                        Need help filling this out? <a href="settings.html#support">Talk to our team →</a>
                    </div>
                </div>
            </aside>

            <main class="onb-main">

                @if(session('success'))
                    <div class="onb-alert onb-alert--success">
                        <div class="onb-alert__icon">✓</div>
                        <div class="onb-alert__content">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="onb-alert onb-alert--danger">
                        <div class="onb-alert__icon">!</div>
                        <div class="onb-alert__content">
                            <strong>There were some problems:</strong>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')

            </main>

        </div>
    </div>

 <script src="{{ asset('js/onboarding.js') }}"></script>

</body>

</html> --}}


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Profile - Flecso</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/app_icon.png') }}">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/onboarding.css') }}">

    <style>
        :root {
            --onb-bg: #f6f8fb;
            --onb-card: #ffffff;
            --onb-text: #101828;
            --onb-muted: #667085;
            --onb-soft: #eef4ff;
            --onb-border: rgba(16, 24, 40, 0.10);
            --onb-shadow: 0 24px 70px rgba(16, 24, 40, 0.10);
            --onb-shadow-soft: 0 14px 34px rgba(16, 24, 40, 0.08);
            --onb-primary: #1d4ed8;
            --onb-primary-2: #2563eb;
            --onb-primary-soft: rgba(37, 99, 235, 0.10);
            --onb-success: #16a34a;
            --onb-danger: #dc2626;
            --onb-radius-lg: 28px;
            --onb-radius-md: 18px;
            --onb-radius-sm: 12px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Inter, sans-serif;
            color: var(--onb-text);
            background:
                radial-gradient(circle at top left, rgba(37, 99, 235, 0.15), transparent 34%),
                radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.12), transparent 30%),
                var(--onb-bg);
        }

        .onb {
            width: 100%;
            min-height: 100vh;
            padding: 22px;
            background-color: #fff;
        }

        .onb-topbar {
            max-width: 1220px;
            margin: 0 auto 18px;
            min-height: 74px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 14px 18px;
            border: 1px solid var(--onb-border);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.82);
            backdrop-filter: blur(18px);
            box-shadow: 0 10px 30px rgba(16, 24, 40, 0.06);
        }

        .onb-topbar__brand {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .brand-mark {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 46px;
            height: 46px;
            border-radius: 16px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.12), rgba(14, 165, 233, 0.10));
            border: 1px solid rgba(37, 99, 235, 0.12);
            overflow: hidden;
        }

        .brand-text {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-text img {
            display: block;
            max-width: 120px;
            height: auto;
            object-fit: contain;
        }

        .onb-topbar__brand strong {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -0.03em;
            color: var(--onb-text);
        }

        .onb-exit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 42px;
            padding: 10px 14px;
            border: 1px solid rgba(37, 99, 235, 0.16);
            border-radius: 999px;
            color: #ff5500;
            background: var(--onb-primary-soft);
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            transition: transform 0.2s ease, background 0.2s ease, box-shadow 0.2s ease;
            white-space: nowrap;
        }

        .onb-exit:hover {
            transform: translateY(-1px);
            background: rgba(37, 99, 235, 0.15);
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.14);
        }

        .onb-stepper {
            max-width: 1220px;
            margin: 0 auto 18px;
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .onb-step {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
            min-height: 76px;
            padding: 14px;
            border: 1px solid var(--onb-border);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.72);
            backdrop-filter: blur(14px);
            box-shadow: 0 10px 28px rgba(16, 24, 40, 0.045);
            overflow: hidden;
        }

        .onb-step::before {
            content: "";
            position: absolute;
            inset: auto 0 0 0;
            height: 3px;
            background: transparent;
            transition: background 0.2s ease;
        }

        .onb-step__num {
            width: 38px;
            height: 38px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            color: var(--onb-muted);
            background: #f2f4f7;
            border: 1px solid rgba(16, 24, 40, 0.08);
            font-size: 14px;
            font-weight: 800;
        }

        .onb-step small {
            display: block;
            margin-bottom: 3px;
            color: var(--onb-muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .onb-step div:last-child div {
            color: var(--onb-text);
            font-size: 14px;
            font-weight: 800;
            letter-spacing: -0.02em;
            line-height: 1.25;
        }

        .onb-step.active {
            border-color: rgba(255,107,26,.30);
            background: linear-gradient(180deg, rgba(255, 107, 26, .10), rgba(255, 107, 26, .10));
            box-shadow: 0 16px 42px rgba(37, 99, 235, 0.12);
        }

        .onb-step.active::before {
            background: linear-gradient(135deg,#FF8A2B 0%,#FF5500 100%);
        }

        .onb-step.active .onb-step__num {
            color: #fff;
            background: linear-gradient(135deg,#FF8A2B 0%,#FF5500 100%);
            border-color: transparent;
            box-shadow: 0 10px 22px rgba(37, 99, 235, 0.24);
        }

        .onb-step.done {
            border-color: #ff550038;;
            background: #ff550008;;
        }

        .onb-step.done::before {
            background: linear-gradient(135deg,#FF8A2B 0%,#FF5500 100%);
        }

        .onb-step.done .onb-step__num {
            color: #fff;
            background: #FF5500;
            border-color: transparent;
        }

        .onb-shell {
            max-width: 1220px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: minmax(320px, 410px) minmax(0, 1fr);
            gap: 18px;
            align-items: stretch;
        }

        .onb-side {
            position: relative;
            min-height: 650px;
            border-radius: var(--onb-radius-lg);
            overflow: hidden;
            background:
                linear-gradient(135deg,#FF8A2B 0%,#FF5500 100%);
            box-shadow: var(--onb-shadow);
        }

        .onb-side::before {
            content: "";
            position: absolute;
            width: 260px;
            height: 260px;
            right: -92px;
            top: -90px;
            border-radius: 50%;
            background: rgba(96, 165, 250, 0.24);
            filter: blur(4px);
        }

        .onb-side::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            left: -100px;
            bottom: -90px;
            border-radius: 50%;
            background: rgba(14, 165, 233, 0.20);
            filter: blur(4px);
        }

        .onb-side__inner {
            position: relative;
            z-index: 1;
            min-height: 100%;
            padding: 34px;
            display: flex;
            flex-direction: column;
        }

        .onb-side__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            width: fit-content;
            margin-bottom: 24px;
            padding: 8px 12px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            color: rgba(255, 255, 255, 0.84);
            background: rgba(255, 255, 255, 0.10);
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 5px rgba(34, 197, 94, 0.15);
        }

        .onb-side-pane {
            display: none;
            animation: onbFade 0.28s ease;
        }

        .onb-side-pane.active {
            display: block;
        }

        @keyframes onbFade {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .onb-side-pane h2 {
            max-width: 350px;
            margin: 0 0 14px;
            color: #fff;
            font-size: clamp(30px, 4vw, 46px);
            line-height: 1.03;
            font-weight: 800;
            letter-spacing: -0.055em;
        }

        .onb-side-pane h2 em {
            color: #000;
            font-style: normal;
        }

        .onb-side__sub {
            max-width: 360px;
            margin: 0;
            color: #fff;
            font-size: 15px;
            line-height: 1.7;
        }

        .onb-tips {
            display: grid;
            gap: 12px;
            margin-top: 28px;
        }

        .onb-tip {
            display: flex;
            gap: 12px;
            padding: 15px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.10);
            backdrop-filter: blur(12px);
        }

        .onb-tip__icon {
            width: 34px;
            height: 34px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            color: #dbeafe;
            background: rgba(255, 255, 255, 0.13);
            border: 1px solid rgba(255, 255, 255, 0.13);
        }

        .onb-tip h4 {
            margin: 0 0 4px;
            color: #fff;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .onb-tip p {
            margin: 0;
            color: rgba(255, 255, 255, 0.68);
            font-size: 13px;
            line-height: 1.55;
        }

        .onb-help {
            margin-top: auto;
            padding-top: 24px;
            color: rgba(255, 255, 255, 0.68);
            font-size: 14px;
            line-height: 1.5;
        }

        .onb-help a {
            color: #000;
            font-weight: 800;
            text-decoration: none;
        }

        .onb-help a:hover {
            color: #fff;
            text-decoration: underline;
        }

        .onb-main {
            min-width: 0;
            min-height: 650px;
            padding: 34px;
            border: 1px solid var(--onb-border);
            border-radius: var(--onb-radius-lg);
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(18px);
            box-shadow: var(--onb-shadow);
        }

        .onb-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 15px 16px;
            border-radius: 16px;
            margin-bottom: 20px;
            font-family: Inter, sans-serif;
            font-size: 14px;
            line-height: 1.5;
            box-shadow: 0 12px 28px rgba(16, 24, 40, 0.06);
        }

        .onb-alert__icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            flex-shrink: 0;
        }

        .onb-alert__content {
            min-width: 0;
        }

        .onb-alert--danger {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #9f1239;
        }

        .onb-alert--danger .onb-alert__icon {
            background: #ffe4e6;
            color: #be123c;
        }

        .onb-alert--success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .onb-alert--success .onb-alert__icon {
            background: #dcfce7;
            color: #15803d;
        }

        .onb-alert ul {
            margin: 6px 0 0;
            padding-left: 18px;
        }

        .onb-alert li + li {
            margin-top: 3px;
        }

        /*
            Form elements coming from @yield('content') ke liye safe frontend polish.
            Backend names/fields/Blade logic ko touch nahi karta.
        */
        .onb-main form {
            width: 100%;
        }

        .onb-main input,
        .onb-main select,
        .onb-main textarea {
            max-width: 100%;
            border-radius: 12px;
        }

        .onb-main input:focus,
        .onb-main select:focus,
        .onb-main textarea:focus {
            outline: none;
            border-color: rgba(37, 99, 235, 0.55);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .onb-main button,
        .onb-main .btn,
        .onb-main [type="submit"] {
            transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .onb-main button:hover,
        .onb-main .btn:hover,
        .onb-main [type="submit"]:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 1100px) {
            .onb {
                padding: 16px;
            }

            .onb-shell {
                grid-template-columns: 340px minmax(0, 1fr);
            }

            .onb-side__inner,
            .onb-main {
                padding: 26px;
            }

            .onb-stepper {
                gap: 10px;
            }

            .onb-step {
                padding: 12px;
            }
        }

        @media (max-width: 920px) {
            .onb-stepper {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .onb-shell {
                grid-template-columns: 1fr;
            }

            .onb-side {
                min-height: auto;
            }

            .onb-side__inner {
                min-height: auto;
            }

            .onb-main {
                min-height: auto;
            }

            .onb-tips {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .onb-help {
                margin-top: 24px;
            }
        }

        @media (max-width: 640px) {
            .onb {
                padding: 12px;
            }

            .onb-topbar {
                align-items: flex-start;
                border-radius: 18px;
                padding: 13px;
            }

            .onb-topbar__brand {
                gap: 10px;
            }

            .brand-mark {
                min-width: 42px;
                height: 42px;
                border-radius: 14px;
            }

            .brand-text img {
                max-width: 96px;
            }

            .onb-topbar__brand strong {
                display: none;
            }

            .onb-exit {
                min-height: 39px;
                padding: 9px 11px;
                font-size: 13px;
            }

            .onb-stepper {
                grid-template-columns: 1fr;
                gap: 8px;
            }

            .onb-step {
                min-height: 64px;
                border-radius: 15px;
            }

            .onb-step__num {
                width: 34px;
                height: 34px;
                border-radius: 12px;
            }

            .onb-side,
            .onb-main {
                border-radius: 20px;
            }

            .onb-side__inner,
            .onb-main {
                padding: 20px;
            }

            .onb-side-pane h2 {
                font-size: 30px;
            }

            .onb-side__sub {
                font-size: 14px;
            }

            .onb-tips {
                grid-template-columns: 1fr;
                margin-top: 20px;
            }

            .onb-tip {
                padding: 13px;
                border-radius: 16px;
            }

            .onb-alert {
                padding: 13px;
                border-radius: 14px;
            }
        }

        @media (max-width: 420px) {
            .onb-topbar {
                gap: 10px;
            }

            .onb-exit svg {
                display: none;
            }

            .onb-side__inner,
            .onb-main {
                padding: 16px;
            }

            .onb-step div:last-child div {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>

    <div class="onb">

        <header class="onb-topbar">
            <img src="{{ asset('images/app_icon.png') }}" alt="Flecso Logo" width="40px" height="40px">
            <a class="onb-exit" id="onbExit" type="button" href="{{ route('logout') }}">
                Save &amp; exit
                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"></path>
                </svg>
            </a>
        </header>

        <nav class="onb-stepper">

            <div class="onb-step {{ $step >= 1 ? ($step == 1 ? 'active' : 'done') : '' }}">
                <div class="onb-step__num">1</div>
                <div>
                    <small>Step 1</small>
                    <div>Company info</div>
                </div>
            </div>

            <div class="onb-step {{ $step >= 2 ? ($step == 2 ? 'active' : 'done') : '' }}">
                <div class="onb-step__num">2</div>
                <div>
                    <small>Step 2</small>
                    <div>Address & comms</div>
                </div>
            </div>

            <div class="onb-step {{ $step >= 3 ? ($step == 3 ? 'active' : 'done') : '' }}">
                <div class="onb-step__num">3</div>
                <div>
                    <small>Step 3</small>
                    <div>Fleet & operations</div>
                </div>
            </div>

            <div class="onb-step {{ $step >= 4 ? ($step == 4 ? 'active' : 'done') : '' }}">
                <div class="onb-step__num">4</div>
                <div>
                    <small>Step 4</small>
                    <div>Representative</div>
                </div>
            </div>

        </nav>

        <div class="onb-shell">

            <aside class="onb-side">
                <div class="onb-side__inner">
                    <span class="onb-side__eyebrow"><span class="dot"></span> Almost there</span>

                    <!-- Step-specific copy -->
                    <div class="onb-side-pane active" data-step="1">
                        <h2>Tell us about your <em>business</em></h2>
                        <p class="onb-side__sub">
                            We need a few legal details to issue invoices, run KYC, and stay fully
                            compliant with Italian regulations.
                        </p>

                        <div class="onb-tips">
                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4>Required for compliance</h4>
                                    <p>Italian fiscal authorities require a verified VAT and fiscal code on file.</p>
                                </div>
                            </div>

                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="m22 6-10 7L2 6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4>Used on invoices</h4>
                                    <p>The legal name appears on every BoL and invoice we generate for you.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="onb-side-pane" data-step="2">
                        <h2>Where can we <em>reach you?</em></h2>
                        <p class="onb-side__sub">
                            Italian e-invoicing requires a PEC inbox and an SDI code. We'll route
                            every electronic invoice through them.
                        </p>

                        <div class="onb-tips">
                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 22s7-7 7-12a7 7 0 1 0-14 0c0 5 7 12 7 12Z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <h4>Registered office only</h4>
                                    <p>This is the legal address on file — separate from your operational depots.</p>
                                </div>
                            </div>

                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                        <path d="m22 6-10 7L2 6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4>PEC for legal email</h4>
                                    <p>Posta Elettronica Certificata — your certified Italian e-mail address.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="onb-side-pane" data-step="3">
                        <h2>Your <em>fleet</em>, your <em>licences</em></h2>
                        <p class="onb-side__sub">
                            Tell us how big your operation is. This calibrates dashboards, billing
                            tier, and which features we surface first.
                        </p>

                        <div class="onb-tips">
                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M3 7h10v10H3z"></path>
                                        <path d="M13 10h5l3 3v4h-8"></path>
                                        <circle cx="7" cy="18" r="2"></circle>
                                        <circle cx="17" cy="18" r="2"></circle>
                                    </svg>
                                </div>
                                <div>
                                    <h4>REN required for hauliers</h4>
                                    <p>Your Registro Elettronico Nazionale number unlocks freight features.</p>
                                </div>
                            </div>

                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4>EU licence is optional</h4>
                                    <p>Only needed if you operate cross-border within the European Union.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="onb-side-pane" data-step="4">
                        <h2>Verify the <em>legal representative</em></h2>
                        <p class="onb-side__sub">
                            A signed identity document of the company's legal representative —
                            required for KYC under Italian and EU AML rules.
                        </p>

                        <div class="onb-tips">
                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h4>Stored encrypted</h4>
                                    <p>Documents are encrypted at rest and only accessible to compliance staff.</p>
                                </div>
                            </div>

                            <div class="onb-tip">
                                <div class="onb-tip__icon">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h4>JPG, PNG or PDF · max 5 MB</h4>
                                    <p>A clear scan or photograph of an official identity document is enough.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="onb-help">
                        Need help filling this out? <a href="settings.html#support">Talk to our team →</a>
                    </div>
                </div>
            </aside>

            <main class="onb-main">

                @if(session('success'))
                    <div class="onb-alert onb-alert--success">
                        <div class="onb-alert__icon">✓</div>
                        <div class="onb-alert__content">
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="onb-alert onb-alert--danger">
                        <div class="onb-alert__icon">!</div>
                        <div class="onb-alert__content">
                            <strong>There were some problems:</strong>
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')

            </main>

        </div>
    </div>

    <script src="{{ asset('js/onboarding.js') }}"></script>

</body>

</html>

