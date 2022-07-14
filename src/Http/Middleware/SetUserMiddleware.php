<?php
namespace RA\Auth\Http\Middleware;

use RA\Auth\Services\SetUser;

class SetUserMiddleware
{
    public function handle($request, \Closure $next)
    {
        SetUser::run($request);
        return $next($request);
    }
}
