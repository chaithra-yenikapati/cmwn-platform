<?php

namespace app\Http\Middleware;

use Closure;

class Api
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $origin = $request->header('origin');

        if (preg_match("`^https?://([0-9a-zA-Z-_]+\.)?changemyworldnow.com(:[0-9]+)?/?$`i", $origin) ||
            preg_match("`^https?://([0-9a-zA-Z-_]+\.)?cmwn.localhost(:[0-9]+)?/?$`i", $origin)
        ) {
            return $next($request)->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Credentials', 'true')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, OPTIONS, PUT, DELETE')
                ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Authorization, X-Auth-Token, X-CSRF-TOKEN')
                ->header('Access-Control-Max-Age', '28800')
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        } else {
            return $next($request);
        }
    }
}
