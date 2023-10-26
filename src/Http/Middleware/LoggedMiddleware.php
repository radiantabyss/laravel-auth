<?php
namespace Lumi\Auth\Http\Middleware;

use Lumi\Core\Response;
use Lumi\Auth\Services\SetUser;

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
