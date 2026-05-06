<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\ActivityLog;

class ActivityLogger
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Skip static assets
        if ($request->is('assets/*', 'storage/*', 'favicon.ico')) {
            return $response;
        }
        if ($request->isMethod('GET')) {
            return $response;
        }

        // Only authenticated users
        if (!auth()->check()) {
            return $response;
        }

        // ✅ Cache-based dedup — same user + method + path within 3 seconds = skip
        $dedupeKey = 'activity_log:'
            . auth()->id() . ':'
            . $request->method() . ':'
            . md5($request->path());

        if (Cache::has($dedupeKey)) {
            return $response;
        }

        // Lock this key for 3 seconds
        Cache::put($dedupeKey, true, now()->addSeconds(3));

        // Detect model + id from route
        $model   = null;
        $modelId = null;

        if ($request->route()) {
            $routeName = optional($request->route())->getName() ?? '';
            $parameters = $request->route()->parameters();

            foreach ($parameters as $param) {

                // ✅ Case 1: Route Model Binding (e.g. Truck $truck)
                if (is_object($param) && method_exists($param, 'getKey')) {
                    $model   = class_basename($param);
                    $modelId = $param->getKey();
                    break;
                }

                // ✅ Case 2: Plain {id} integer — guess model from route name
                // e.g. "trucks.show" → "Truck", "drivers.show" → "Driver"
                if (is_numeric($param)) {
                    $modelId = (int) $param;

                    // Extract from route name: "trucks.show" → "trucks" → "Truck"
                    $prefix = explode('.', $routeName)[0] ?? null;
                    if ($prefix) {
                        $model = ucfirst(rtrim($prefix, 's')); // trucks→Truck, drivers→Driver
                    }
                    break;
                }
            }
        }

        // Payload — query params for GET, body for POST
        $payload = $request->isMethod('GET')
            ? $request->query()
            : $request->except(['password', '_token', 'password_confirmation']);

        // Response — JSON message or HTTP status
        $responseData = ['http_status' => $response->getStatusCode()];

        if (method_exists($response, 'getContent')) {
            $json = json_decode($response->getContent(), true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($json)) {
                $responseData = [
                    'status'  => $json['status']  ?? null,
                    'message' => $json['message'] ?? null,
                ];
            }
        }

        ActivityLog::create([
            'user_id'    => auth()->id(),
            'action'     => optional($request->route())->getName() ?? $request->path(),
            'method'     => $request->method(),
            'route'      => $request->path(),
            'model'      => $model,
            'model_id'   => $modelId,
            'payload'    => $payload,
            'response'   => $responseData,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return $response;
    }
}
