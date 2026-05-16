@extends('layouts.app')

@section('content')

<style>
    :root {
        --primary: #247faf;
        --orange: #ff7a21;
        --soft-bg: #f6f8fb;
        --border: #e9eef5;
        --text: #101828;
        --muted: #667085;
        --white: #ffffff;
    }

    .user-page {
        padding: 26px 28px;
        background: var(--soft-bg);
        min-height: 100vh;
        font-family: Inter, Arial, sans-serif;
    }

    .user-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 22px;
    }

    .breadcrumb-custom {
        color: var(--muted);
        font-size: 14px;
    }

    .breadcrumb-custom a {
        color: var(--primary);
        text-decoration: none;
        font-weight: 500;
    }

    .btn-back {
        background: #fff;
        color: var(--primary);
        border: 1px solid var(--border);
        padding: 11px 18px;
        border-radius: 9px;
        font-weight: 800;
        text-decoration: none;
    }

    .form-card {
        background: var(--white);
        border-radius: 14px;
        box-shadow: 0 8px 22px rgba(16, 24, 40, 0.06);
        border: 1px solid var(--border);
        overflow: hidden;
        max-width: 820px;
    }

    .form-card-head {
        background: var(--primary);
        color: #fff;
        padding: 22px 24px;
    }

    .form-card-head h3 {
        margin: 0;
        font-size: 21px;
        font-weight: 900;
    }

    .form-card-head p {
        margin: 7px 0 0;
        font-size: 13px;
        opacity: 0.92;
    }

    .form-body {
        padding: 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 18px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-group.full {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: var(--text);
        font-size: 14px;
        font-weight: 800;
    }

    .form-control-custom {
        width: 100%;
        height: 46px;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 0 14px;
        outline: none;
        color: var(--text);
        font-size: 14px;
        background: #fff;
    }

    .form-control-custom:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 4px rgba(36, 127, 175, 0.10);
    }

    .help-text {
        margin-top: 7px;
        color: var(--muted);
        font-size: 12px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 26px;
        border-top: 1px solid var(--border);
        padding-top: 20px;
    }

    .btn-cancel {
        border: 1px solid var(--border);
        background: #fff;
        color: var(--muted);
        border-radius: 9px;
        padding: 12px 18px;
        font-weight: 800;
        text-decoration: none;
    }

    .btn-save {
        border: 0;
        background: var(--orange);
        color: #fff;
        border-radius: 9px;
        padding: 12px 20px;
        font-weight: 900;
        cursor: pointer;
    }

    .alert-error-custom {
        background: #fff1f3;
        color: #b42318;
        border: 1px solid #fecdd3;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 16px;
        font-weight: 600;
        max-width: 820px;
    }

    @media (max-width: 760px) {
        .user-top {
            align-items: flex-start;
            flex-direction: column;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-cancel,
        .btn-save {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="user-page">

    <div class="user-top">
        <div class="breadcrumb-custom">
            <a href="{{ url('/dashboard') }}">Dashboard</a>
            <span> / </span>
            <a href="{{ route('admin.users-management.index') }}">User Management</a>
            <span> / Create</span>
        </div>

        <a href="{{ route('admin.users-management.index') }}" class="btn-back">
            Back to Users
        </a>
    </div>

    @if($errors->any())
        <div class="alert-error-custom">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="form-card">
        <div class="form-card-head">
            <h3>Create Child User</h3>
            <p>This user will use parent admin data and assigned role permissions.</p>
        </div>

        <form action="{{ route('admin.users-management.store') }}" method="POST">
            @csrf

            <div class="form-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>User Name</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            class="form-control-custom"
                            placeholder="Enter user name"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Phone</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            class="form-control-custom"
                            placeholder="Enter phone number"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="form-control-custom"
                            placeholder="Enter email address">
                    </div>

                    <div class="form-group">
                        <label>Assign Role</label>
                        <select name="role_id" class="form-control-custom" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="help-text">Only roles created by this parent admin will show here.</div>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input
                            type="password"
                            name="password"
                            class="form-control-custom"
                            placeholder="Enter password"
                            required>
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control-custom"
                            placeholder="Confirm password"
                            required>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.users-management.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-save">Create User</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection