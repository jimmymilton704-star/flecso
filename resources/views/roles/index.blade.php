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
        --danger: #f04438;
        --white: #ffffff;
    }

    .page-wrap {
        padding: 26px 28px;
        background: var(--soft-bg);
        min-height: 100vh;
        font-family: Inter, Arial, sans-serif;
    }

    .top-row {
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

    .role-card {
        background: var(--white);
        border-radius: 14px;
        box-shadow: 0 8px 22px rgba(16, 24, 40, 0.06);
        overflow: hidden;
        border: 1px solid var(--border);
    }

    .card-head {
        padding: 18px 22px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        border-bottom: 1px solid var(--border);
    }

    .card-head h3 {
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

    .table-head {
        display: grid;
        grid-template-columns: 80px 1.2fr 1fr 1fr 160px;
        padding: 15px 22px;
        background: var(--primary);
        color: #fff;
        font-weight: 800;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .role-list {
        padding: 14px 16px 22px;
    }

    .role-row {
        display: grid;
        grid-template-columns: 80px 1.2fr 1fr 1fr 160px;
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

    .role-name-box {
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .role-avatar {
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

    .role-title {
        font-weight: 900;
        color: var(--text);
        margin-bottom: 3px;
    }

    .role-sub {
        color: var(--muted);
        font-size: 12px;
    }

    .permission-link {
        background: #f2f6ff;
        color: var(--primary);
        border-radius: 7px;
        padding: 8px 13px;
        text-decoration: none;
        font-weight: 800;
        font-size: 13px;
        display: inline-flex;
        width: fit-content;
        align-items: center;
        gap: 7px;
    }

    .guard-pill {
        width: fit-content;
        padding: 7px 13px;
        background: var(--orange-light);
        color: var(--orange);
        border-radius: 20px;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
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

    .custom-modal {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.55);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
    }

    .custom-modal.active {
        display: flex;
    }

    .modal-card {
        width: 100%;
        max-width: 520px;
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 22px 60px rgba(15, 23, 42, 0.25);
    }

    .modal-head {
        padding: 18px 22px;
        background: var(--primary);
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-head h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
    }

    .modal-close {
        border: 0;
        background: rgba(255,255,255,0.18);
        color: #fff;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 20px;
    }

    .modal-body {
        padding: 22px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        font-weight: 800;
        font-size: 13px;
        color: var(--text);
    }

    .form-control-custom {
        width: 100%;
        height: 44px;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 0 13px;
        outline: none;
        font-size: 14px;
    }

    .modal-footer-custom {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 20px;
    }

    .btn-cancel {
        border: 1px solid var(--border);
        background: #fff;
        color: var(--muted);
        border-radius: 9px;
        padding: 11px 18px;
        font-weight: 800;
        cursor: pointer;
    }

    .btn-save {
        border: 0;
        background: var(--orange);
        color: #fff;
        border-radius: 9px;
        padding: 11px 18px;
        font-weight: 800;
        cursor: pointer;
    }

    @media (max-width: 900px) {
        .table-head {
            display: none;
        }

        .role-row {
            grid-template-columns: 1fr;
            align-items: flex-start;
        }

        .action-box {
            justify-content: flex-start;
        }
    }
</style>

<div class="page-wrap">

    <div class="top-row">
        <div class="breadcrumb-custom">
            <a href="{{ url('/dashboard') }}">Dashboard</a>
            <span> / </span>
            <a href="{{ route('admin.roles.index') }}">Role section</a>
            <span> / Lists</span>
        </div>

        <button type="button" class="btn-main" onclick="openCreateModal()">
            + Add Role
        </button>
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

    <div class="role-card">
        <div class="card-head">
            <h3>Role List</h3>
            <span class="total-badge">{{ $totalRoles }} Total</span>
        </div>

        <form method="GET" class="filter-row">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                class="filter-input"
                placeholder="Search role or guard name">

            <button type="submit" class="btn-filter">Filter</button>
            <a href="{{ route('admin.roles.index') }}" class="btn-reset">Reset</a>
        </form>

        <div class="table-head">
            <div># ID</div>
            <div>Role Name</div>
            <div>Permissions</div>
            <div>Guard Type</div>
            <div style="text-align:right;">Actions</div>
        </div>

        <div class="role-list">
            @forelse($roles as $role)
                <div class="role-row">
                    <div>
                        <span class="id-badge">#{{ $role->id }}</span>
                    </div>

                    <div class="role-name-box">
                        <div class="role-avatar">
                            {{ strtoupper(substr($role->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="role-title">{{ $role->name }}</div>
                            <div class="role-sub">Security Level access</div>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('admin.roles.permissions', $role->id) }}" class="permission-link">
                            🔑 Assign Permissions
                            <span>({{ $role->permissions_count }})</span>
                        </a>
                    </div>

                    <div>
                        <span class="guard-pill">{{ $role->guard_name }}</span>
                    </div>

                    <div class="action-box">
                        <button
                            type="button"
                            class="icon-btn edit-btn"
                            onclick="openEditModal(
                                '{{ $role->id }}',
                                '{{ addslashes($role->name) }}',
                                '{{ $role->guard_name }}'
                            )">
                            ✎
                        </button>

                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this role?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-btn delete-btn">🗑</button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="padding: 30px; text-align:center; color: var(--muted);">
                    No roles found.
                </div>
            @endforelse
        </div>

        @if($roles->hasPages())
            <div class="footer-pagination">
                <div>
                    Showing {{ $roles->firstItem() ?? 0 }} to {{ $roles->lastItem() ?? 0 }} of {{ $roles->total() }}
                </div>
                <div>
                    {{ $roles->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<div class="custom-modal" id="roleModal">
    <div class="modal-card">
        <div class="modal-head">
            <h3 id="modalTitle">Add Role</h3>
            <button type="button" class="modal-close" onclick="closeModal()">×</button>
        </div>

        <form method="POST" id="roleForm">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="modal-body">
                <div class="form-group">
                    <label>Role Name</label>
                    <input type="text" name="name" id="roleName" class="form-control-custom" placeholder="example: admin" required>
                </div>

                <div class="form-group">
                    <label>Guard Name</label>
                    <input type="text" name="guard_name" id="guardName" class="form-control-custom" value="web" required>
                </div>

                <div class="modal-footer-custom">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn-save">Save Role</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const modal = document.getElementById('roleModal');
    const form = document.getElementById('roleForm');
    const methodInput = document.getElementById('formMethod');
    const modalTitle = document.getElementById('modalTitle');

    function openCreateModal() {
        modalTitle.innerText = 'Add Role';
        form.action = "{{ route('admin.roles.store') }}";
        methodInput.value = 'POST';

        document.getElementById('roleName').value = '';
        document.getElementById('guardName').value = 'web';

        modal.classList.add('active');
    }

    function openEditModal(id, name, guardName) {
        modalTitle.innerText = 'Edit Role';
        form.action = "{{ url('admin/roles') }}/" + id;
        methodInput.value = 'PUT';

        document.getElementById('roleName').value = name;
        document.getElementById('guardName').value = guardName;

        modal.classList.add('active');
    }

    function closeModal() {
        modal.classList.remove('active');
    }

    window.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });
</script>

@endsection