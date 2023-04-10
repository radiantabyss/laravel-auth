<?php
namespace RA\Auth\Http\Middleware;

use RA\Core\Response;
use RA\Auth\Services\SetUser;

class LoggedMiddleware
{
    public function handle($request, \Closure $next)
    {
        $response = SetUser::run($request);

        if ( $response !== true ) {
            return Response::error($response);
        }

        return $next($request);
    }
}
