<?php
namespace RA\Auth\Http\Middleware;

use RA\Response;

class TeamRoleMiddleware
{
    public function handle($request, \Closure $next, $role) {
        $roles = explode('|', $role);

        //check user role in team
        if ( !in_array(\Auth::user()->team->role, $roles) ) {
            return Response::error('Not allowed.');
        }

        return $next($request);
    }
}
