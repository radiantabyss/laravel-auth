<?php
namespace Lumi\Auth\Http\Middleware;

use Lumi\Auth\Services\SetUser;

class SetUserMiddleware
{
    public function handle($request, \Closure $next)
    {
        SetUser::run($request);
        return $next($request);
    }
}
