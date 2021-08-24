<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class Subscription
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
        $requestData = $request->only(['client_token', 'app_id']);
        if (Cache::has("subscription:" . $requestData['client_token'] . ":" . $requestData['app_id'])) {

            $subscription = Cache::get("subscription:" . $requestData['client_token'] . ":" . $requestData['app_id']);
            return response()->json(['device' => $subscription], 200);

        } else {

            return $next($request);

        }

    }
}
