<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('guard_name', 'like', '%' . $request->search . '%')
                    ->orWhere('group', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('group')) {
            $query->where('group', $request->group);
        }

        $permissions = $query->latest()->paginate(10)->withQueryString();

        $groups = Permission::query()
            ->whereNotNull('group')
            ->where('group', '!=', '')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');

        $totalPermissions = Permission::count();

        return view('permissions.index', compact(
            'permissions',
            'groups',
            'totalPermissions'
        ));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')
                    ->where('guard_name', $request->guard_name ?? 'web'),
            ],
            'group' => 'nullable|string|max:255',
            'guard_name' => 'required|string|max:255',
        ]);

        Permission::create([
            'name' => $request->name,
            'group' => $request->group,
            'guard_name' => $request->guard_name,
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('permissions', 'name')
                    ->where('guard_name', $request->guard_name ?? 'web')
                    ->ignore($permission->id),
            ],
            'group' => 'nullable|string|max:255',
            'guard_name' => 'required|string|max:255',
        ]);

        $permission->update([
            'name' => $request->name,
            'group' => $request->group,
            'guard_name' => $request->guard_name,
        ]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }
}