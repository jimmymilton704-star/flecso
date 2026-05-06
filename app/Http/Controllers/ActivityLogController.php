<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();

        // 🔍 Search (action, route, model, user name)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('action', 'like', "%$search%")
                    ->orWhere('route', 'like', "%$search%")
                    ->orWhere('model', 'like', "%$search%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%$search%");
                    });
            });
        }

        // ✅ Filter by action (since UI now uses action, not method)
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // 📅 Filter by date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // 📄 Pagination
        $logs = $query->paginate(10)->withQueryString();

        return view('activity-logs.index', compact('logs'));
    }
}
