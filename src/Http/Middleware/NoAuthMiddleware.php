<?php
namespace RA\Auth\Http\Middleware;

use App\Core\Response;

class NoAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        if ( \Auth::check() ) {
            if ( env('RA_AUTH_LOGIN_STRATEGY') == 'session' ) {
                redirect(config('ra-auth.default_redirect_if_not_logged'));
            }
            else if ( env('RA_AUTH_LOGIN_STRATEGY') == 'jwt' ) {
                return Response::error('Logged users are not allowed.');
            }
        }

        return $next($request);
    }
}
