<?php
namespace RA\Auth\Http\Middleware;

use RA\Response;

class UserTypeMiddleware
{
    public function handle($request, \Closure $next, $type) {
        $types = explode('|', $type);

        //check user type
        if ( !in_array(\Auth::user()->type, $types) ) {
            return Response::error('Not allowed.');
        }

        return $next($request);
    }
}
