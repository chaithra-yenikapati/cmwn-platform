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

        //$ACCESS_CONTROL_ALLOW_ORIGIN = 'http://'.$this->giveHost($request->root());

        return $next($request)->header('Access-Control-Allow-Origin', 'http://dev.changemyworldnow.com')
            ->header('Access-Control-Allow-Credentials', 'true')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, OPTIONS, PUT, DELETE')
            ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Authorization, X-Auth-Token, X-CSRF-TOKEN')
            ->header('Access-Control-Max-Age', '28800');
    }

    private function giveHost($host_with_subdomain)
    {
        $array = explode('.', $host_with_subdomain);

        return (array_key_exists(count($array) - 2, $array) ? $array[count($array) - 2] : '').'.'.$array[count($array) - 1];
    }
}
