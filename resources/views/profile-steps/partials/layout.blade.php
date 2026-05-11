<!DOCTYPE html>
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

</html>