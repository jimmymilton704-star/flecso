<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $authUser = auth()->user();
        $parentId = $authUser->parent_id ?: $authUser->id;

        $query = User::query()
            ->where('parent_id', $parentId)
            ->with('roles');

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        $totalUsers = User::where('parent_id', $parentId)->count();

        return view('admin.users.index', compact('users', 'totalUsers'));
    }

    public function create()
    {
        $authUser = auth()->user();
        $parentId = $authUser->parent_id ?: $authUser->id;

        $roles = Role::where('admin_id', $parentId)
            ->orderBy('name')
            ->get();

        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();
        $parentId = $authUser->parent_id ?: $authUser->id;
        $parentAdmin = User::find($parentId);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:30',
                Rule::unique('users', 'phone'),
            ],
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::where('id', $request->role_id)
            ->where('admin_id', $parentId)
            ->firstOrFail();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $role->name,
            'parent_id' => $parentId,

            /*
        |--------------------------------------------------------------------------
        | Copy Parent Admin Data
        |--------------------------------------------------------------------------
        */
            'profile_completed' => $parentAdmin->profile_completed,
            'avatar' => $parentAdmin->avatar,

            'company_name' => $parentAdmin->company_name,
            'company_legal_name' => $parentAdmin->company_legal_name,
            'company_type' => $parentAdmin->company_type,

            'vat_number' => $parentAdmin->vat_number,
            'fiscal_code' => $parentAdmin->fiscal_code,
            'rea_number' => $parentAdmin->rea_number,
            'pec_email' => $parentAdmin->pec_email,
            'sdi_code' => $parentAdmin->sdi_code,

            'registered_address' => $parentAdmin->registered_address,
            'city' => $parentAdmin->city,
            'province' => $parentAdmin->province,
            'zip_code' => $parentAdmin->zip_code,

            'ren_number' => $parentAdmin->ren_number,
            'eu_license_number' => $parentAdmin->eu_license_number,

            'fleet_trucks' => $parentAdmin->fleet_trucks,
            'fleet_vans' => $parentAdmin->fleet_vans,
            'fleet_containers' => $parentAdmin->fleet_containers,

            'insurance_policy_number' => $parentAdmin->insurance_policy_number,

            'rep_full_name' => $parentAdmin->rep_full_name,
            'rep_position' => $parentAdmin->rep_position,
            'rep_fiscal_code' => $parentAdmin->rep_fiscal_code,
            'rep_document' => $parentAdmin->rep_document,

            /*
        |--------------------------------------------------------------------------
        | Verification Data Optional
        |--------------------------------------------------------------------------
        | Usually child should not need OTP data copied.
        | Keep these null for security.
        |--------------------------------------------------------------------------
        */
            'email_verified_at' => $parentAdmin->email_verified_at,
            'phone_verified_at' => $parentAdmin->phone_verified_at,


        ]);

        $user->assignRole($role->name);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.users-management.index')
            ->with('success', 'User created and role assigned successfully.');
    }

    public function edit(User $users_management)
    {
        $user = $users_management;

        $authUser = auth()->user();
        $parentId = $authUser->parent_id ?: $authUser->id;

        if ($user->parent_id != $parentId) {
            abort(403, 'Unauthorized user access.');
        }

        $roles = Role::where('admin_id', $parentId)
            ->orderBy('name')
            ->get();

        $selectedRoleId = optional($user->roles()->first())->id;

        return view('admin.users.edit', compact(
            'user',
            'roles',
            'selectedRoleId'
        ));
    }

    public function update(Request $request, User $users_management)
    {
        $user = $users_management;

        $authUser = auth()->user();
        $parentId = $authUser->parent_id ?: $authUser->id;

        if ($user->parent_id != $parentId) {
            abort(403, 'Unauthorized user access.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users', 'phone')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $role = Role::where('id', $request->role_id)
            ->where('admin_id', $parentId)
            ->firstOrFail();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $role->name,
            'parent_id' => $parentId,

        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        $user->syncRoles([$role->name]);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        return redirect()
            ->route('admin.users-management.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $users_management)
    {
        $user = $users_management;

        $authUser = auth()->user();
        $parentId = $authUser->parent_id ?: $authUser->id;

        if ($user->parent_id != $parentId) {
            abort(403, 'Unauthorized user access.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users-management.index')
            ->with('success', 'User deleted successfully.');
    }
}
