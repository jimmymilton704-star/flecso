@extends('layouts.app')

@section('content')

<style>
    :root {
        --primary: #247faf;
        --primary-dark: #17648c;
        --soft-bg: #f6f8fb;
        --module-head: #e9ecef;
        --border: #dfe5ec;
        --text: #111827;
        --muted: #667085;
        --white: #ffffff;
        --shadow: rgba(16, 24, 40, 0.08);
    }

    .permission-setting-page {
        background: var(--soft-bg);
        min-height: 100vh;
        padding: 26px 28px 45px;
        font-family: Inter, Arial, sans-serif;
    }

    .permission-hero {
        background: var(--primary);
        color: #fff;
        border-radius: 8px;
        padding: 28px 34px;
        margin-bottom: 28px;
    }

    .permission-hero h1 {
        margin: 0;
        font-size: 29px;
        font-weight: 900;
        letter-spacing: 0.2px;
    }

    .permission-hero-meta {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 14px;
        font-size: 14px;
        font-weight: 600;
        color: rgba(255,255,255,0.95);
    }

    .assign-badge {
        background: #eef0ff;
        color: #3f51b5;
        border-radius: 20px;
        padding: 4px 13px;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
    }

    .permission-main-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 40px 34px 34px;
        box-shadow: 0 2px 9px rgba(16, 24, 40, 0.04);
    }

    .role-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 40px;
    }

    .role-tab {
        min-width: 110px;
        height: 32px;
        padding: 0 20px;
        border-radius: 30px;
        border: 1px solid #8b9bb0;
        background: #fff;
        color: #6b7890;
        font-size: 13px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.2s ease;
    }

    .role-tab:hover {
        background: #eef7fc;
        border-color: var(--primary);
        color: var(--primary);
    }

    .role-tab.active {
        background: var(--primary);
        color: #fff;
        border-color: var(--primary);
        font-weight: 800;
    }

    .module-card {
        background: #fff;
        border: 1px solid var(--border);
        box-shadow: 0 2px 7px var(--shadow);
        margin-bottom: 22px;
    }

    .module-header {
        min-height: 50px;
        padding: 0 22px;
        background: var(--module-head);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .module-title {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #000;
        font-size: 15px;
        font-weight: 900;
    }

    .module-title svg {
        color: var(--primary);
        flex-shrink: 0;
    }

    .module-check-all {
        display: flex;
        align-items: center;
        gap: 7px;
        color: #0067a6;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        user-select: none;
    }

    .switch-input {
        display: none;
    }

    .switch-slider {
        width: 26px;
        height: 14px;
        background: #b8b8b8;
        border-radius: 20px;
        position: relative;
        display: inline-block;
        transition: 0.2s ease;
    }

    .switch-slider::before {
        content: "";
        width: 10px;
        height: 10px;
        background: #fff;
        border-radius: 50%;
        position: absolute;
        top: 2px;
        left: 2px;
        transition: 0.2s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.25);
    }

    .switch-input:checked + .switch-slider {
        background: var(--primary);
    }

    .switch-input:checked + .switch-slider::before {
        transform: translateX(12px);
    }

    .module-body {
        padding: 22px;
        background: #fff;
    }

    .permission-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .permission-item {
        height: 36px;
        border: 1px solid var(--border);
        background: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 0 9px;
        color: #66728a;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        user-select: none;
    }

    .permission-item:hover {
        border-color: var(--primary);
        background: #f7fbfe;
    }

    .permission-item input {
        width: 16px;
        height: 16px;
        margin: 0;
        accent-color: var(--primary);
        cursor: pointer;
    }

    .permission-item span {
        line-height: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .permission-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 28px;
    }

    .btn-back {
        height: 42px;
        padding: 0 22px;
        border-radius: 6px;
        border: 1px solid var(--border);
        background: #fff;
        color: var(--muted);
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-save {
        height: 42px;
        padding: 0 26px;
        border-radius: 6px;
        border: 0;
        background: var(--primary);
        color: #fff;
        font-size: 14px;
        font-weight: 900;
        cursor: pointer;
    }

    .btn-save:hover {
        background: var(--primary-dark);
    }

    .alert-success-custom {
        background: #ecfdf3;
        color: #027a48;
        border: 1px solid #abefc6;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-weight: 600;
    }

    .alert-error-custom {
        background: #fff1f3;
        color: #b42318;
        border: 1px solid #fecdd3;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 18px;
        font-weight: 600;
    }

    @media (max-width: 1200px) {
        .permission-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 850px) {
        .permission-setting-page {
            padding: 20px 14px 35px;
        }

        .permission-main-card {
            padding: 28px 18px;
        }

        .permission-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 560px) {
        .permission-hero {
            padding: 22px 18px;
        }

        .permission-hero h1 {
            font-size: 24px;
        }

        .permission-grid {
            grid-template-columns: 1fr;
        }

        .module-header {
            height: auto;
            padding: 14px;
            gap: 12px;
            flex-direction: column;
            align-items: flex-start;
        }

        .permission-actions {
            flex-direction: column;
        }

        .btn-back,
        .btn-save {
            width: 100%;
        }
    }
</style>

<div class="permission-setting-page">

    @if(session('success'))
        <div class="alert-success-custom">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert-error-custom">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="permission-hero">
        <h1>Permission Setting</h1>

        <div class="permission-hero-meta">
            <span class="assign-badge">Assign</span>
            <span>🔑 Managing permissions for: {{ ucfirst($role->name) }}</span>
        </div>
    </div>

    <div class="permission-main-card">

        <div class="role-tabs">
            @forelse($roles as $singleRole)
                <a href="{{ route('admin.roles.permissions', $singleRole->id) }}"
                   class="role-tab {{ $singleRole->id == $role->id ? 'active' : '' }}">
                    {{ ucfirst($singleRole->name) }}
                </a>
            @empty
                <span style="color:#667085;">No roles found.</span>
            @endforelse
        </div>

        <form action="{{ route('admin.roles.permissions.sync', $role->id) }}" method="POST">
            @csrf

            @forelse($permissions as $groupName => $groupPermissions)
                @php
                    $moduleKey = \Illuminate\Support\Str::slug($groupName);
                @endphp

                <div class="module-card" data-module="{{ $moduleKey }}">
                    <div class="module-header">
                        <div class="module-title">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                                <path d="M12 3L21 7.5V16.5L12 21L3 16.5V7.5L12 3Z" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 12L21 7.5" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 12V21" stroke="currentColor" stroke-width="2"/>
                                <path d="M12 12L3 7.5" stroke="currentColor" stroke-width="2"/>
                            </svg>

                            <span>{{ ucfirst($groupName) }} Module</span>
                        </div>

                        <label class="module-check-all">
                            <input type="checkbox" class="switch-input module-check-toggle">
                            <span class="switch-slider"></span>
                            <span>Check All</span>
                        </label>
                    </div>

                    <div class="module-body">
                        <div class="permission-grid">
                            @foreach($groupPermissions as $permission)
                                <label class="permission-item">
                                    <input
                                        type="checkbox"
                                        class="permission-checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->id }}"
                                        {{ in_array($permission->id, $rolePermissionIds) ? 'checked' : '' }}>

                                    <span>{{ ucwords(str_replace('_', ' ', $permission->name)) }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @empty
                <div class="module-card">
                    <div class="module-body" style="text-align:center; color:#667085;">
                        No permissions found.
                    </div>
                </div>
            @endforelse

            <div class="permission-actions">
                <a href="{{ route('admin.roles.index') }}" class="btn-back">Back</a>
                <button type="submit" class="btn-save">Save Permissions</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.module-card').forEach(function (moduleCard) {
            updateModuleSwitch(moduleCard);
        });
    });

    document.querySelectorAll('.module-check-toggle').forEach(function (toggle) {
        toggle.addEventListener('change', function () {
            const moduleCard = this.closest('.module-card');
            const checkboxes = moduleCard.querySelectorAll('.permission-checkbox');

            checkboxes.forEach(function (checkbox) {
                checkbox.checked = toggle.checked;
            });
        });
    });

    document.querySelectorAll('.permission-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', function () {
            const moduleCard = this.closest('.module-card');
            updateModuleSwitch(moduleCard);
        });
    });

    function updateModuleSwitch(moduleCard) {
        const checkboxes = moduleCard.querySelectorAll('.permission-checkbox');
        const toggle = moduleCard.querySelector('.module-check-toggle');

        if (!toggle || checkboxes.length === 0) {
            return;
        }

        let allChecked = true;

        checkboxes.forEach(function (checkbox) {
            if (!checkbox.checked) {
                allChecked = false;
            }
        });

        toggle.checked = allChecked;
    }
</script>

@endsection