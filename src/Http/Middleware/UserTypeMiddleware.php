<?php
namespace Lumi\Auth\Http\Middleware;

use Lumi\Core\Response;

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
