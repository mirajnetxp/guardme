<?php

namespace Responsive\Http\Middleware;

use Closure;

class Partners
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
        if(auth()->check() && auth()->user()->admin == 3)
		 {
            
			}
		 else
		 {
			  return redirect('index');
		 }
		return $next($request);
    }
}
