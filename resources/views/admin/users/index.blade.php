@extends('layouts.app')

@section('content')

<style>
    :root {
        --primary: #247faf;
        --primary-dark: #17648c;
        --orange: #ff7a21;
        --orange-light: #fff2e8;
        --light-blue: #eef7fc;
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

    .btn-main {
        background: var(--primary);
        color: #fff;
        border: 0;
        padding: 12px 20px;
        border-radius: 9px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        box-shadow: 0 10px 22px rgba(36, 127, 175, 0.18);
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-main:hover {
        background: var(--primary-dark);
        color: #fff;
    }

    .user-card {
        background: var(--white);
        border-radius: 14px;
        box-shadow: 0 8px 22px rgba(16, 24, 40, 0.06);
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .user-card-head {
        padding: 18px 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        border-bottom: 1px solid var(--border);
    }

    .user-card-head h3 {
        margin: 0;
        font-size: 17px;
        color: var(--text);
        font-weight: 800;
    }

    .total-badge {
        background: #eef0ff;
        color: var(--primary);
        padding: 6px 14px;
        border-radius: 30px;
        font-weight: 800;
        font-size: 12px;
    }

    .filter-row {
        padding: 18px 22px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        border-bottom: 1px solid var(--border);
        background: #fbfcfe;
    }

    .filter-input {
        height: 42px;
        border: 1px solid var(--border);
        border-radius: 9px;
        padding: 0 14px;
        outline: none;
        font-size: 14px;
        background: #fff;
        color: var(--text);
        min-width: 280px;
    }

    .btn-filter {
        height: 42px;
        border: 0;
        border-radius: 9px;
        padding: 0 18px;
        background: var(--orange);
        color: #fff;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-reset {
        height: 42px;
        border-radius: 9px;
        padding: 0 18px;
        background: #fff;
        color: var(--muted);
        border: 1px solid var(--border);
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .user-table-head {
        display: grid;
        grid-template-columns: 80px 1.3fr 1.2fr 1fr 1fr 150px;
        padding: 15px 22px;
        background: var(--primary);
        color: #fff;
        font-weight: 800;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .user-list {
        padding: 14px 16px 22px;
    }

    .user-row {
        display: grid;
        grid-template-columns: 80px 1.3fr 1.2fr 1fr 1fr 150px;
        align-items: center;
        gap: 12px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 16px 18px;
        margin-bottom: 10px;
        min-height: 72px;
    }

    .id-badge {
        background: #f1f3f6;
        color: var(--text);
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        display: inline-block;
        width: fit-content;
    }

    .user-name-box {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .user-avatar {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: var(--primary);
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        box-shadow: 0 8px 16px rgba(36, 127, 175, 0.22);
    }

    .user-title {
        font-weight: 900;
        color: var(--text);
        margin-bottom: 3px;
    }

    .user-sub {
        color: var(--muted);
        font-size: 12px;
    }

    .role-pill {
        width: fit-content;
        padding: 7px 13px;
        background: var(--orange-light);
        color: var(--orange);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .parent-pill {
        width: fit-content;
        padding: 7px 13px;
        background: var(--light-blue);
        color: var(--primary);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
    }

    .action-box {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .icon-btn {
        width: 38px;
        height: 38px;
        border-radius: 9px;
        border: 1px solid var(--border);
        background: #fff;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .edit-btn {
        color: var(--primary);
        background: #f4f8ff;
    }

    .delete-btn {
        color: var(--orange);
        background: #fff7f2;
    }

    .footer-pagination {
        border-top: 1px solid var(--border);
        padding: 16px 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--muted);
        font-size: 14px;
        flex-wrap: wrap;
        gap: 16px;
    }

    .alert-success-custom {
        background: #ecfdf3;
        color: #027a48;
        border: 1px solid #abefc6;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 16px;
        font-weight: 600;
    }

    .alert-error-custom {
        background: #fff1f3;
        color: #b42318;
        border: 1px solid #fecdd3;
        padding: 12px 16px;
        border-radius: 10px;
        margin-bottom: 16px;
        font-weight: 600;
    }

    @media (max-width: 1000px) {
        .user-table-head {
            display: none;
        }

        .user-row {
            grid-template-columns: 1fr;
            align-items: flex-start;
        }

        .action-box {
            justify-content: flex-start;
        }
    }
</style>

<div class="user-page">

    <div class="user-top">
        <div class="breadcrumb-custom">
            <a href="{{ url('/dashboard') }}">Dashboard</a>
            <span> / </span>
            <a href="{{ route('admin.users-management.index') }}">User Management</a>
            <span> / Lists</span>
        </div>

        <a href="{{ route('admin.users-management.create') }}" class="btn-main">
            + Add User
        </a>
    </div>

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

    <div class="user-card">
        <div class="user-card-head">
            <h3>User Management List</h3>
            <span class="total-badge">{{ $totalUsers }} Total</span>
        </div>

        <form method="GET" class="filter-row">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="filter-input"
                placeholder="Search user name, email or phone">

            <button type="submit" class="btn-filter">Filter</button>
            <a href="{{ route('admin.users-management.index') }}" class="btn-reset">Reset</a>
        </form>

        <div class="user-table-head">
            <div># ID</div>
            <div>User Name</div>
            <div>Email</div>
            <div>Phone</div>
            <div>Role</div>
            <div style="text-align:right;">Actions</div>
        </div>

        <div class="user-list">
            @forelse($users as $user)
                <div class="user-row">
                    <div>
                        <span class="id-badge">#{{ $user->id }}</span>
                    </div>

                    <div class="user-name-box">
                        <div class="user-avatar">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="user-title">{{ $user->name }}</div>
                            <div class="user-sub">Child user of parent admin</div>
                        </div>
                    </div>

                    <div>{{ $user->email ?? 'N/A' }}</div>

                    <div>{{ $user->phone }}</div>

                    <div>
                        <span class="role-pill">
                            {{ optional($user->roles->first())->name ?? $user->role ?? 'No Role' }}
                        </span>
                    </div>

                    <div class="action-box">
                        <a href="{{ route('admin.users-management.edit', $user->id) }}" class="icon-btn edit-btn">
                            ✎
                        </a>

                        <form action="{{ route('admin.users-management.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-btn delete-btn">🗑</button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="padding: 30px; text-align:center; color: var(--muted);">
                    No users found.
                </div>
            @endforelse
        </div>

        @if($users->hasPages())
            <div class="footer-pagination">
                <div>
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }}
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

@endsection