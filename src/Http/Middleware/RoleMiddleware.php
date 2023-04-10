<?php
namespace RA\Auth\Http\Middleware;

use RA\Core\Response;

class RoleMiddleware
{
    public function handle($request, \Closure $next, $role)
    {
        //check if user type is super admin
        if ( \Auth::user()->type == 'super_admin' ) {
            return $next($request);
        }

        $roles = explode('|', $role);

        //check user type
        if ( !in_array(\Auth::user()->role, $roles) ) {
            return Response::error('Not found.');
        }

        return $next($request);
    }
}
