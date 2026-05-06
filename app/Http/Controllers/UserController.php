<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function companystore(Request $request)
    {
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'company_legal_name' => 'nullable|string|max:255',
            'company_type' => 'nullable|string|max:255',

            'vat_number' => 'nullable|string|max:50',
            'fiscal_code' => 'nullable|string|max:100',
            'rea_number' => 'nullable|string|max:50',

            'pec_email' => 'nullable|email|max:255',
            'sdi_code' => 'nullable|string|max:50',

            'registered_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'province' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',

            'fleet_trucks' => 'nullable|string|max:255',
            'fleet_vans' => 'nullable|string|max:255',
            'fleet_containers' => 'nullable|string|max:255',

            'insurance_policy_number' => 'nullable|string|max:100',
        ]);

        $user = auth()->user();

        $user->update($request->only([
            'company_name',
            'company_legal_name',
            'company_type',
            'vat_number',
            'fiscal_code',
            'rea_number',
            'pec_email',
            'sdi_code',
            'registered_address',
            'city',
            'province',
            'zip_code',
            'fleet_trucks',
            'fleet_vans',
            'fleet_containers',
            'insurance_policy_number',
        ]));

        return back()->with('success', 'Company data updated successfully.');
    }
    public function profileUpdate(Request $request)
    {
        // dd($request->all(), $request->file('avatar'));
        $request->validate([
            'first_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:30',
            'password' => 'nullable|min:6',
            'avatar' => 'nullable|image|max:2048',
            'two_factor' => 'nullable',
        ]);

        $user = auth()->user();

        $data = [
            'name'  => $request->first_name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        // password update
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // avatar upload
        if ($request->hasFile('avatar')) {

            $file = $request->file('avatar');

            $destination = public_path('uploads/profile');

            // ensure folder exists
            if (!file_exists($destination)) {
                mkdir($destination, 0777, true);
            }

            $name = time() . '_' . $file->getClientOriginalName();

            $file->move($destination, $name);

            $data['avatar'] = 'uploads/profile/' . $name;
        }

        // dd($data);
        // two factor
        // $data['two_factor'] = $request->has('two_factor') ? 1 : 0;

        $user->update($data);

        return redirect()->route('setting')->withFragment('personal')
            ->with('success', 'Profile updated successfully.');
    }
}
