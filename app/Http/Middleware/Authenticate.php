<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middelware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo( $request)
    {
        if(!$request->expectsJson()){
            if($request->routeIs('admin.*')){
                return route('admin.login');
            }
            if ($request->routeIs('staff.*')) {
                return route('staff.login');
            }
            return route('customer.login');
        }
    }
}
