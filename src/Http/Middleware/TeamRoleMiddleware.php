<?php
namespace RA\Auth\Http\Middleware;

use RA\Core\Response;

class TeamRoleMiddleware
{
    public function handle($request, \Closure $next, $role)
    {
        //check if user type is super admin
        if ( \Auth::user()->type == 'super_admin' ) {
            return $next($request);
        }

        $roles = explode('|', $role);

        //check user role in team
        if ( !in_array(\Auth::user()->team->role, $roles) ) {
            return Response::error('Not found.');
        }

        return $next($request);
    }
}
