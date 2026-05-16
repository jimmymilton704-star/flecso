<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $authUser = auth()->user();

        $parentId = $authUser->parent_id ?: $authUser->id;

        /*
        |--------------------------------------------------------------------------
        | Get parent admin + all child users IDs
        |--------------------------------------------------------------------------
        */
        $userIds = User::where('parent_id', $parentId)
            ->pluck('id')
            ->toArray();

        $userIds[] = $parentId;

        /*
        |--------------------------------------------------------------------------
        | Fetch logs by user_id only
        |--------------------------------------------------------------------------
        */
        $query = ActivityLog::with('user')
            ->whereIn('user_id', $userIds);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('action', 'like', '%' . $request->search . '%')
                    ->orWhere('route', 'like', '%' . $request->search . '%')
                    ->orWhere('method', 'like', '%' . $request->search . '%')
                    ->orWhere('model', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('method')) {
            $query->where('method', $request->method);
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        return view('activity-logs.index', compact('logs'));
    }
}