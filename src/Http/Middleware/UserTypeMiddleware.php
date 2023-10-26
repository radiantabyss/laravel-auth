<?php
namespace Lumi\Auth\Http\Middleware;

use Lumi\Core\Response;

class UserTypeMiddleware
{
    public function handle($request, \Closure $next, $type)
    {
        //check if user type is super admin
        if ( \Auth::user()->type == 'super_admin' ) {
            return $next($request);
        }

        $types = explode('|', $type);

        //check user type
        if ( !in_array(\Auth::user()->type, $types) ) {
            return Response::error('Not found.');
        }

        return $next($request);
    }
}
