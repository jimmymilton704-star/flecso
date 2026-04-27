<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SosAlert;
use Illuminate\Http\Request;

class SosAlertController extends Controller
{
    /*
    |-----------------------------------------
    | ADMIN: GET ALL SOS ALERTS
    |-----------------------------------------
    */
    public function index(Request $request)
    {
        $adminId = auth()->id();

        $query = SosAlert::with(['driver', 'trip'])
            ->where('admin_id', $adminId)
            ->latest();

        // Optional filter
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        return response()->json([
            'status' => true,
            'data' => $query->get()
        ]);
    }

    /*
    |-----------------------------------------
    | ADMIN: SINGLE SOS
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
            return response()->json([
                'status' => false,
                'message' => 'SOS not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $alert
        ]);
    }

    /*
    |-----------------------------------------
    | ADMIN: RESOLVE SOS
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
            return response()->json([
                'status' => false,
                'message' => 'SOS not found'
            ], 404);
        }

        $alert->update([
            'status' => 'resolved'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'SOS marked as resolved'
        ]);
    }
}