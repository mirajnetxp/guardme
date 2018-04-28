<?php

namespace Responsive\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class ApiResponseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set('Content-Type','application/json');
        $request->headers->set('X-Requested-With','XMLHttpRequest');

        return $next($request);
    }
}
