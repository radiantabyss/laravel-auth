<?php
namespace Lumi\Auth\Http\Middleware;

use Lumi\Core\Response;

class NotLoggedMiddleware
{
    public function handle($request, \Closure $next)
    {
        if ( \Auth::check() ) {
            return Response::error('Logged users are not allowed.');
        }

        return $next($request);
    }
}
