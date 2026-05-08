<?php

namespace App\Http\Controllers;

use App\Models\SosAlert;
use Illuminate\Http\Request;

class SosAlertController extends Controller
{
    /*
    |-----------------------------------------
    | LIST ALL SOS ALERTS (WEB)
    |-----------------------------------------
    */
    public function index(Request $request)
    {
        $adminId = auth()->id();

        $query = SosAlert::with(['driver:id,full_name', 'trip'])
            ->where('admin_id', $adminId)
            ->latest();

        // Optional filter (active / resolved)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $alerts = $query->paginate(10);

        return view('sos.index', [
            'alerts' => $alerts,
            'status' => $request->status
        ]);
    }

    /*
    |-----------------------------------------
    | SINGLE SOS VIEW
    |-----------------------------------------
    */
    public function show($id)
    {
        $adminId = auth()->id();

        $alert = SosAlert::with(['driver', 'trip'])
            ->where('id', $id)
            ->where('admin_id', $adminId)
            ->first();

        if (!$alert) {
            return redirect()->route('sos.index')
                ->with('error', 'SOS Alert not found');
        }

        return view('sos.show', [
            'alert' => $alert
        ]);
    }

    /*
    |-----------------------------------------
    | RESOLVE SOS (WEB)
    |-----------------------------------------
    */
    public function resolve(Request $request)
    {
        $adminId = auth()->id();

        $request->validate([
            'sos_id' => 'required|exists:sos_alerts,id',
        ]);

        $alert = SosAlert::where('id', $request->sos_id)
            ->where('admin_id', $adminId)
            ->first();

        if (!$alert) {
            return back()->with('error', 'SOS not found');
        }

        $alert->update([
            'status' => 'resolved'
        ]);

        return back()->with('success', 'SOS marked as resolved');
    }
}