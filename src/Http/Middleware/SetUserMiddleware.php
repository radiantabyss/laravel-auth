<?php
namespace RA\Auth\Http\Middleware;

use RA\Auth\Services\Login;

class SetUserMiddleware
{
    public function handle($request, \Closure $next)
    {
        Login::run($request);
        return $next($request);
    }
}
