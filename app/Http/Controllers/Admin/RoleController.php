<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::withCount('permissions');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('guard_name', 'like', '%' . $request->search . '%');
            });
        }

        $roles = $query->latest()->paginate(10)->withQueryString();

        $totalRoles = Role::count();

        return view('roles.index', compact(
            'roles',
            'totalRoles'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')->where('guard_name', $request->guard_name ?? 'web'),
            ],
            'guard_name' => 'required|string|max:255',
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
            'admin_id' => auth()->id(),
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role created successfully.');
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles', 'name')
                    ->where('guard_name', $request->guard_name ?? 'web')
                    ->ignore($role->id),
            ],
            'guard_name' => 'required|string|max:255',
        ]);

        $role->update([
            'name' => $request->name,
            'guard_name' => $request->guard_name,
        ]);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role deleted successfully.');
    }

    public function permissions(Role $role)
    {
        $authAdminId = auth()->id();

        /*
    |--------------------------------------------------------------------------
    | Roles according to admin_id
    |--------------------------------------------------------------------------
    | If you want super-admin to see all roles, you can add condition later.
    */
        $roles = Role::where('admin_id', $authAdminId)
            ->orderBy('name')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Security check
    |--------------------------------------------------------------------------
    | Prevent one admin from opening another admin role permissions.
    */
        if ($role->admin_id != $authAdminId) {
            abort(403, 'Unauthorized role access.');
        }

        $role->load('permissions');

        /*
    |--------------------------------------------------------------------------
    | Permissions grouped by module/group name
    |--------------------------------------------------------------------------
    */
        $permissions = Permission::query()
            ->orderBy('group')
            ->orderBy('name')
            ->get()
            ->groupBy(function ($permission) {
                return $permission->group ?: 'General';
            });

        $rolePermissionIds = $role->permissions->pluck('id')->toArray();

        return view('roles.permissions', compact(
            'role',
            'roles',
            'permissions',
            'rolePermissionIds'
        ));
    }

    public function syncPermissions(Request $request, Role $role)
    {
        if ($role->admin_id != auth()->id()) {
            abort(403, 'Unauthorized role access.');
        }

        $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $permissionNames = Permission::whereIn('id', $request->permissions ?? [])
            ->pluck('name')
            ->toArray();

        $role->syncPermissions($permissionNames);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.roles.permissions.sync', $role->id)
            ->with('success', 'Permissions updated successfully.');
    }
}
