<?php
namespace RA\Auth\Http\Middleware;

use RA\Response;
use RA\Auth\Services\SetUser;

class LoggedMiddleware
{
    public function handle($request, \Closure $next) {
        if ( !\Auth::check() ) {
            return Response::error('Not logged.');
        }

        return $next($request);
    }
}
