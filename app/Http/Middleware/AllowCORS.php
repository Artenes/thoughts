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

        return $response;

    }

}