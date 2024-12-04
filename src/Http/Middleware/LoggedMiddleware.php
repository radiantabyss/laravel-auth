<?php
namespace Lumi\Auth\Http\Middleware;

use Lumi\Core\Response;
use Lumi\Auth\Services\SetUser;

class LoggedMiddleware
{
    public function handle($request, \Closure $next) {
        if ( !\Auth::check() ) {
            return Response::error('Not logged.');
        }

        return $next($request);
    }
}
