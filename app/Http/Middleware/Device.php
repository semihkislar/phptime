<?php

namespace App\Http\Middleware;

use App\Services\ApiService\Response;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Device
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $requestData = $request->only(['udid', 'app_id']);
        if (Cache::has("device:" . $requestData['udid'] . ":" . $requestData['app_id'])) {
            $device = $device = Cache::get("device:" . $requestData['udid'] . ":" . $requestData['app_id']);

            return Response::success(['device' => $device]);
        } else {
            return $next($request);
        }
    }
}
