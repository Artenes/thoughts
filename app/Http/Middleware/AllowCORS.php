<?php

namespace Thoughts\Http\Middleware;

use Closure;

/**
 * Allow CORS for easy testings.
 *
 * @package Sabichona\Http\Middleware
 */
class AllowCORS
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if(config('app.env') === 'production')
            return $next($request);

        $response = $next($request);

        $response->headers->add(['Access-Control-Allow-Origin' => '*']);
        $response->headers->add(['Access-Control-Allow-Headers' => 'Content-Type, Authorization, Cache-Control, X-Requested-With']);
        $response->headers->add(['Access-Control-Allow-Credentials' => 'true']);
        $response->headers->add(['Access-Control-Allow-Methods' => 'GET, POST, PUT, PATCH, DELETE, HEAD, OPTIONS']);

        return $response;

    }

}