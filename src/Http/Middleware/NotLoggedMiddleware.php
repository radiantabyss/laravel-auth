<?php
namespace RA\Auth\Http\Middleware;

use RA\Response;

class NotLoggedMiddleware
{
    public function handle($request, \Closure $next) {
        if ( \Auth::check() ) {
            return Response::error('Logged users are not allowed.');
        }

        return $next($request);
    }
}
