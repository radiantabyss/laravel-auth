<?php
namespace RA\Auth\Http\Middleware;

use RA\Core\Response;
use RA\Auth\Services\SetUser;

class AuthMiddleware
{
    public function handle($request, \Closure $next)
    {
        $response = SetUser::run($request);

        if ( $response !== true ) {
            if ( config('ra-auth.login_strategy') == 'session' ) {
                if ( !in_array($_SERVER['REQUEST_URI'], ['/login', '/logout']) ) {
                    session(['redirect' => $_SERVER['REQUEST_URI']]);
                }

                return redirect(config('ra-auth.default_redirect_if_not_logged'));
            }
            else if ( config('ra-auth.login_strategy') == 'jwt' ) {
                return Response::error($response);
            }
        }

        return $next($request);
    }
}
