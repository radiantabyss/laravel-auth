<?php
namespace RA\Auth\Http\Middleware;

use App\Core\Response;
use RA\Auth\Services\Login;

class AuthMiddleware
{
    public function handle($request, \Closure $next)
    {
        $response = Login::run($request);

        if ( $response !== true ) {
            if ( env('RA_AUTH_LOGIN_STRATEGY') == 'session' ) {
                if ( !in_array($_SERVER['REQUEST_URI'], ['/login', '/logout']) ) {
                    session(['redirect' => $_SERVER['REQUEST_URI']]);
                }

                return redirect(config('ra-auth.default_redirect_if_not_logged'));
            }
            else if ( env('RA_AUTH_LOGIN_STRATEGY') == 'jwt' ) {
                return Response::error($response);
            }
        }

        return $next($request);
    }
}
