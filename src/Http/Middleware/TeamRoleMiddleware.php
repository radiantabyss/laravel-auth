<?php
namespace Lumi\Auth\Http\Middleware;

use Lumi\Core\Response;

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
