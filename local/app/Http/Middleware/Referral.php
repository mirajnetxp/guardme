<?php

namespace Responsive\Http\Middleware;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Support\Facades\Auth;
use Closure;
use Responsive\User;

/**
 * Class Referral
 * Middleware for referral link
 *
 * @package Responsive\Http\Middleware
 */
class Referral
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * The response factory implementation.
     *
     * @var ResponseFactory
     */
    protected $response;

    /**
     * Create a new filter instance.
     *
     * @param  Guard $auth
     * @param  ResponseFactory $response
     * @return void
     */
    public function __construct(Guard $auth, ResponseFactory $response)
    {
        $this->auth = $auth;
        $this->response = $response;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //Is not authenticated
        if (!auth()->check()) {
            //Get unique part of url
            if ($uid = $request->get('uid')) {

                $user = User::where('name', $uid)->first();
                if ($user && $user->id) {
                    session(['referral' => $user->name]);
                }
            }

        } else {
            session(['referral' => '']);
        }
        return $next($request);
    }


}
