@extends('layouts.app')

@section('title', 'Setting')
@section('body-class', 'page-dashboard')

@php
    $user = user();
@endphp

@section('content')
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}" />

    <section class="page">
        <div class="page-head">
            <div>
                <div class="breadcrumb">System <span>/ Settings</span></div>
                <h1>Settings</h1>
                <div class="page-head__sub">Configure your company profile, permissions, preferences, and subscription.</div>
            </div>
        </div>

        <div class="tabs-vertical">
            <nav class="tabs-list">
                <button class="tab-item active" data-tab="company">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path
                            d="M3 22V4a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v18M15 22V11h6v11M6 7h2M6 11h2M6 15h2M12 7h-2M12 11h-2M12 15h-2" />
                    </svg>
                    <span>Company Profile</span>
                </button>

                <button class="tab-item" data-tab="personal">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="12" cy="8" r="4" />
                        <path d="M4 21c0-4 4-7 8-7s8 3 8 7" />
                    </svg>
                    <span>Personal Profile</span>
                </button>



                {{-- <button class="tab-item" data-tab="language">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20" />
                    </svg>
                    <span>Language</span>
                </button> --}}

                <button class="tab-item" data-tab="subscription">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="m12 2 2.4 7.4H22l-6.2 4.5 2.4 7.4L12 16.8l-6.2 4.5 2.4-7.4L2 9.4h7.6z" />
                    </svg>
                    <span>Subscription Plans</span>
                </button>

                <button class="tab-item" data-tab="legal">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
                    </svg>
                    <span>Legal Pages</span>
                </button>

                <button class="tab-item" data-tab="support">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01" />
                    </svg>
                    <span>Help & Support</span>
                </button>
            </nav>

            <div>
                <!-- Company -->
                <div class="tab-panel active" data-panel="company">
                    <form action="{{ route('users.company.store') }}" method="POST">
                        @csrf

                        <div class="card">
                            <div class="card__head">
                                <div class="card__title">
                                    <h3>Company Profile</h3>
                                </div>
                                <button type="submit" class="btn btn--primary btn--sm">Save changes</button>
                            </div>

                            <div class="card__body">

                                <div class="form-grid">

                                    <div class="field">
                                        <label>Company Name</label>
                                        <input class="input" name="company_name"
                                            value="{{ old('company_name', $user?->company_name) }}">
                                    </div>

                                    <div class="field">
                                        <label>Legal Name</label>
                                        <input class="input" name="company_legal_name"
                                            value="{{ old('company_legal_name', $user?->company_legal_name) }}">
                                    </div>

                                    <div class="field">
                                        <label>Company Type</label>
                                        <input class="input" name="company_type"
                                            value="{{ old('company_type', $user?->company_type) }}">
                                    </div>

                                    <div class="field">
                                        <label>VAT Number</label>
                                        <input class="input" name="vat_number"
                                            value="{{ old('vat_number', $user?->vat_number) }}">
                                    </div>

                                    <div class="field">
                                        <label>Fiscal Code</label>
                                        <input class="input" name="fiscal_code"
                                            value="{{ old('fiscal_code', $user?->fiscal_code) }}">
                                    </div>

                                    <div class="field">
                                        <label>REA Number</label>
                                        <input class="input" name="rea_number"
                                            value="{{ old('rea_number', $user?->rea_number) }}">
                                    </div>

                                    <div class="field">
                                        <label>PEC Email</label>
                                        <input class="input" name="pec_email"
                                            value="{{ old('pec_email', $user?->pec_email) }}">
                                    </div>

                                    <div class="field">
                                        <label>SDI Code</label>
                                        <input class="input" name="sdi_code"
                                            value="{{ old('sdi_code', $user?->sdi_code) }}">
                                    </div>

                                    <div class="field full">
                                        <label>Address</label>
                                        <input class="input" name="registered_address"
                                            value="{{ old('registered_address', $user?->registered_address) }}">
                                    </div>

                                    <div class="field">
                                        <label>City</label>
                                        <input class="input" name="city" value="{{ old('city', $user?->city) }}">
                                    </div>

                                    <div class="field">
                                        <label>Province</label>
                                        <input class="input" name="province"
                                            value="{{ old('province', $user?->province) }}">
                                    </div>

                                    <div class="field">
                                        <label>ZIP Code</label>
                                        <input class="input" name="zip_code"
                                            value="{{ old('zip_code', $user?->zip_code) }}">
                                    </div>

                                    <div class="field">
                                        <label>Fleet Trucks</label>
                                        <input class="input" name="fleet_trucks"
                                            value="{{ old('fleet_trucks', $user?->fleet_trucks) }}">
                                    </div>

                                    <div class="field">
                                        <label>Fleet Vans</label>
                                        <input class="input" name="fleet_vans"
                                            value="{{ old('fleet_vans', $user?->fleet_vans) }}">
                                    </div>

                                    <div class="field">
                                        <label>Fleet Containers</label>
                                        <input class="input" name="fleet_containers"
                                            value="{{ old('fleet_containers', $user?->fleet_containers) }}">
                                    </div>

                                    <div class="field">
                                        <label>Insurance Policy</label>
                                        <input class="input" name="insurance_policy_number"
                                            value="{{ old('insurance_policy_number', $user?->insurance_policy_number) }}">
                                    </div>

                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Personal -->
                <div class="tab-panel" data-panel="personal">
                    <form action="{{ route('users.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <div class="card__head">
                                <div class="card__title">
                                    <h3>Personal Profile</h3>
                                </div>
                                <button type="submit" class="btn btn--primary btn--sm">Update</button>
                            </div>

                            <div class="card__body">
                                <div class="form-section">
                                    <div class="flex items-center gap-12" style="margin-bottom:14px">

                                        <!-- Avatar Box -->
                                        <div id="avatarPreview"
                                            style="width:64px;height:64px;border-radius:50%;overflow:hidden;display:grid;place-items:center;background:var(--ink-900);color:#fff;font-weight:700;font-size:22px">

                                            @if ($user && $user->avatar)
                                                <img src="{{ asset($user->avatar) }}" alt="{{ $user->name }}"
                                                    style="width:100%;height:100%;object-fit:cover">
                                            @else
                                                {{ strtoupper(substr($user?->name ?? 'U', 0, 1)) }}
                                            @endif
                                        </div>

                                        <div>
                                            <h3>{{ $user?->name }}</h3>
                                            <div class="muted">{{ ucfirst($user?->role ?? 'User') }} ·
                                                {{ $user?->company_name ?? 'Company' }}</div>

                                            <!-- Upload Button -->
                                            <label for="avatarInput" class="btn btn--ghost btn--sm"
                                                style="cursor:pointer">
                                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                    stroke="currentColor" stroke-width="2">
                                                    <path
                                                        d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12">
                                                    </path>
                                                </svg>
                                                Upload new
                                            </label>

                                            <!-- Hidden Input -->
                                            <input type="file" name="avatar" id="avatarInput" accept="image/*"
                                                hidden>
                                        </div>
                                    </div>

                                    <div class="form-grid">
                                        <div class="field">
                                            <label>Name</label>
                                            <input class="input" type="text" name="first_name"
                                                value="{{ old('first_name', $user?->first_name ?? $user?->name) }}"
                                                placeholder="Marco" />
                                        </div>



                                        <div class="field">
                                            <label>Email</label>
                                            <input class="input" type="email" name="email"
                                                value="{{ old('email', $user?->email) }}"
                                                placeholder="marco.b@flecso.io" />
                                        </div>

                                        <div class="field">
                                            <label>Phone</label>
                                            <input class="input" type="tel" name="phone"
                                                value="{{ old('phone', $user?->phone) }}"
                                                placeholder="+39 340 551 7802" />
                                        </div>

                                        <div class="field">
                                            <label>Role</label>
                                            <input class="input" type="text" value="{{ $user?->role }}" readonly />
                                        </div>


                                    </div>
                                </div>

                                <div class="form-section">
                                    <h4><span class="sec-num">⚙</span> Security</h4>

                                    <div class="form-grid">
                                        <div class="field">
                                            <label>Current Password</label>
                                            <input class="input" type="password" name="current_password"
                                                placeholder="••••••••" />
                                        </div>

                                        <div class="field">
                                            <label>New Password</label>
                                            <input class="input" type="password" name="password" placeholder="" />
                                        </div>

                                        {{-- <div class="field full">
                                            <label class="checkbox">
                                                <input type="checkbox" name="two_factor" checked>
                                                Enable two-factor authentication (recommended)
                                            </label>
                                        </div> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>



                <!-- Language -->
                {{-- <div class="tab-panel" data-panel="language">
                    <div class="card">
                        <div class="card__head">
                            <div class="card__title">
                                <h3>Language & Region</h3>
                            </div>
                        </div>
                        <div class="card__body">
                            <div class="grid-3">
                                <div class="card"
                                    style="padding:18px;cursor:pointer;border-color:var(--orange-400);box-shadow:0 0 0 4px rgba(255,107,26,.08)">
                                    <div style="font-size:26px">🇬🇧</div>
                                    <h4 style="margin-top:8px">English</h4>
                                    <p class="muted">United Kingdom</p>
                                    <span class="badge badge--orange" style="margin-top:10px"
                                        onclick="changeLang('en')">Active</span>
                                </div>
                                <div class="card" style="padding:18px;cursor:pointer">
                                    <div style="font-size:26px">🇮🇹</div>
                                    <h4 style="margin-top:8px">Italiano</h4>
                                    <p class="muted">Italia</p>
                                    <button class="btn btn--ghost btn--sm" style="margin-top:10px"
                                        onclick="changeLang('it')">Set as default</button>
                                </div>
                                <div class="card" style="padding:18px;cursor:pointer">
                                    <div style="font-size:26px">🇩🇪</div>
                                    <h4 style="margin-top:8px">Deutsch</h4>
                                    <p class="muted">Deutschland</p>
                                    <button class="btn btn--ghost btn--sm" style="margin-top:10px"
                                        onclick="changeLang('de')">Set as default</button>
                                </div>
                                <div class="card" style="padding:18px;cursor:pointer">
                                    <div style="font-size:26px">🇪🇸</div>
                                    <h4 style="margin-top:8px">Español</h4>
                                    <p class="muted">España</p>
                                    <button class="btn btn--ghost btn--sm" style="margin-top:10px"
                                        onclick="changeLang('es')">Set as default</button>
                                </div>
                                <div class="card" style="padding:18px;cursor:pointer">
                                    <div style="font-size:26px">🇵🇱</div>
                                    <h4 style="margin-top:8px">Polski</h4>
                                    <p class="muted">Polska</p>
                                    <button class="btn btn--ghost btn--sm" style="margin-top:10px"
                                        onclick="changeLang('pl')">Set as default</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <!-- Subscription -->
                <div class="tab-panel" data-panel="subscription">
                    <div class="card">
                        <div class="card__head">
                            <div class="card__title">
                                <h3>Subscription Plans</h3>
                            </div>
                            <span class="badge badge--orange">Current: Premium</span>
                        </div>
                        <div class="card__body">
                            <div class="plans">
                                <div class="plan">
                                    <span class="plan-tag">Starter</span>
                                    <h3>Basic</h3>
                                    <p class="muted">Essentials for small fleets</p>
                                    <div class="plan-price">€49 <small>/ month</small></div>
                                    <ul>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>Up to 10 trucks</span></li>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>Basic dashboard</span></li>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>Email support</span></li>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>1 admin user</span></li>
                                    </ul>
                                    <button class="btn btn--ghost btn--block">Upgrade</button>
                                </div>

                                <div class="plan">
                                    <span class="plan-tag">Popular</span>
                                    <h3>Standard</h3>
                                    <p class="muted">Scale your operations</p>
                                    <div class="plan-price">€149 <small>/ month</small></div>
                                    <ul>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>Up to 50 trucks</span></li>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>Advanced analytics</span></li>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>QR asset tracking</span></li>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>Priority email support</span></li>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>5 admin users</span></li>
                                    </ul>
                                    <button class="btn btn--ghost btn--block">Upgrade</button>
                                </div>

                                <div class="plan featured">
                                    <span class="plan-tag">Best Value</span>
                                    <h3>Premium</h3>
                                    <p class="muted" style="color:rgba(255,255,255,.65)">All features, unlimited</p>
                                    <div class="plan-price">€399 <small>/ month</small></div>
                                    <ul>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>Unlimited trucks</span></li>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>AI routing & ETA</span></li>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>Live fleet tracking</span></li>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>24/7 phone support</span></li>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>Unlimited users</span></li>
                                        <li><svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                                stroke="currentColor" stroke-width="2">
                                                <path d="M20 6 9 17l-5-5" />
                                            </svg><span>Dedicated CSM</span></li>
                                    </ul>
                                    <button class="btn btn--primary btn--block">Current Plan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Legal -->
                <div class="tab-panel" data-panel="legal">
                    <div class="card">
                        <div class="card__head">
                            <div class="card__title">
                                <h3>Legal Pages</h3>
                            </div>
                        </div>
                        <div class="card__body">
                            <div
                                style="display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px dashed var(--surface-line)">
                                <div
                                    style="width:40px;height:40px;border-radius:10px;background:var(--ink-50);display:grid;place-items:center;color:var(--ink-600)">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
                                    </svg>
                                </div>
                                <div style="flex:1">
                                    <h5 style="font-size:14px">About Us</h5>
                                    <p class="muted">Our mission, story, and the team building Flecso</p>
                                </div>
                                <button class="btn btn--ghost btn--sm">View</button>
                                <button class="btn btn--ghost btn--sm">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                    </svg>
                                </button>
                            </div>

                            <div
                                style="display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px dashed var(--surface-line)">
                                <div
                                    style="width:40px;height:40px;border-radius:10px;background:var(--ink-50);display:grid;place-items:center;color:var(--ink-600)">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
                                    </svg>
                                </div>
                                <div style="flex:1">
                                    <h5 style="font-size:14px">Privacy Policy</h5>
                                    <p class="muted">How we collect, use, and protect customer data</p>
                                </div>
                                <button class="btn btn--ghost btn--sm">View</button>
                                <button class="btn btn--ghost btn--sm">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                    </svg>
                                </button>
                            </div>

                            <div
                                style="display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px dashed var(--surface-line)">
                                <div
                                    style="width:40px;height:40px;border-radius:10px;background:var(--ink-50);display:grid;place-items:center;color:var(--ink-600)">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
                                    </svg>
                                </div>
                                <div style="flex:1">
                                    <h5 style="font-size:14px">Terms & Conditions</h5>
                                    <p class="muted">Legal terms governing the use of our platform</p>
                                </div>
                                <button class="btn btn--ghost btn--sm">View</button>
                                <button class="btn btn--ghost btn--sm">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                    </svg>
                                </button>
                            </div>

                            <div
                                style="display:flex;align-items:center;gap:14px;padding:14px 0;border-bottom:1px dashed var(--surface-line)">
                                <div
                                    style="width:40px;height:40px;border-radius:10px;background:var(--ink-50);display:grid;place-items:center;color:var(--ink-600)">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
                                    </svg>
                                </div>
                                <div style="flex:1">
                                    <h5 style="font-size:14px">Data Processing Agreement</h5>
                                    <p class="muted">GDPR-compliant DPA for enterprise customers</p>
                                </div>
                                <button class="btn btn--ghost btn--sm">View</button>
                                <button class="btn btn--ghost btn--sm">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                    </svg>
                                </button>
                            </div>

                            <div style="display:flex;align-items:center;gap:14px;padding:14px 0">
                                <div
                                    style="width:40px;height:40px;border-radius:10px;background:var(--ink-50);display:grid;place-items:center;color:var(--ink-600)">
                                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <path d="M14 2v6h6M16 13H8M16 17H8M10 9H8" />
                                    </svg>
                                </div>
                                <div style="flex:1">
                                    <h5 style="font-size:14px">Cookie Policy</h5>
                                    <p class="muted">Information about the cookies we use</p>
                                </div>
                                <button class="btn btn--ghost btn--sm">View</button>
                                <button class="btn btn--ghost btn--sm">
                                    <svg viewBox="0 0 24 24" width="14" height="14" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4Z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Support -->
                <div class="tab-panel" data-panel="support">
                    <div class="grid-3">
                        <div class="card">
                            <div class="card__body" style="text-align:center;padding:30px">
                                <div
                                    style="width:52px;height:52px;margin:0 auto 10px;border-radius:14px;background:var(--orange-50);color:var(--orange-700);display:grid;place-items:center">
                                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01" />
                                    </svg>
                                </div>
                                <h3>Help Center</h3>
                                <p class="muted" style="margin:6px 0 14px">Browse articles, tutorials and guides.</p>
                                <button class="btn btn--ghost">Open Help Center</button>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card__body" style="text-align:center;padding:30px">
                                <div
                                    style="width:52px;height:52px;margin:0 auto 10px;border-radius:14px;background:var(--ink-900);color:#fff;display:grid;place-items:center">
                                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="4" width="20" height="16" rx="2" />
                                        <path d="m22 6-10 7L2 6" />
                                    </svg>
                                </div>
                                <h3>Contact Support</h3>
                                <p class="muted" style="margin:6px 0 14px">Response within 2 hours on business days.</p>
                                <button class="btn btn--ghost">support@flecso.io</button>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card__body" style="text-align:center;padding:30px">
                                <div
                                    style="width:52px;height:52px;margin:0 auto 10px;border-radius:14px;background:var(--success-50);color:var(--success-700);display:grid;place-items:center">
                                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none"
                                        stroke="currentColor" stroke-width="2">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.8 19.8 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.8 19.8 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.37 1.9.72 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.35 1.85.59 2.81.72A2 2 0 0 1 22 16.92Z" />
                                    </svg>
                                </div>
                                <h3>Priority Line</h3>
                                <p class="muted" style="margin:6px 0 14px">24/7 for Premium subscribers.</p>
                                <button class="btn btn--primary">+39 02 9999 0000</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function changeLang(lang) {
            const select = document.querySelector(".goog-te-combo");

            if (!select) {
                alert("Google Translate not loaded yet");
                return;
            }

            select.value = lang;
            select.dispatchEvent(new Event("change"));
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const avatarBtn = document.getElementById('avatarBtn');
            const avatarInput = document.getElementById('avatarInput');
            const avatarPreview = document.getElementById('avatarPreview');

            if (avatarBtn && avatarInput) {
                avatarBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    avatarInput.click();
                });
            }

            if (avatarInput && avatarPreview) {
                avatarInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];

                    if (file) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            avatarPreview.innerHTML =
                                '<img src="' + e.target.result +
                                '" style="width:100%;height:100%;object-fit:cover">';
                        };

                        reader.readAsDataURL(file);
                    }
                });
            }
        });
    </script>
    <script>
        /* Settings page: vertical tab switching + deep-link via hash */
        (function() {
            const tabs = document.querySelectorAll(".tab-item");
            const panels = document.querySelectorAll(".tab-panel");

            function activate(id) {
                tabs.forEach(i =>
                    i.classList.toggle("active", i.dataset.tab === id)
                );

                panels.forEach(p =>
                    p.classList.toggle("active", p.dataset.panel === id)
                );
            }

            function handleHash() {
                const hash = location.hash.replace("#", "");
                if (hash && document.querySelector(`[data-tab="${hash}"]`)) {
                    activate(hash);
                } else {
                    activate("company"); // default tab
                }
            }

            // click switching
            tabs.forEach(ti => {
                ti.addEventListener("click", () => {
                    const id = ti.dataset.tab;
                    activate(id);
                    history.replaceState(null, "", "#" + id);
                });
            });

            // IMPORTANT: run on load
            handleHash();

            // IMPORTANT: run when hash changes (your missing part)
            window.addEventListener("hashchange", handleHash);

        })();
    </script>

@endsection
