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
            if ( config('ra-auth.login_strategy') == 'session' ) {
                redirect(config('ra-auth.default_redirect_if_not_logged'));
            }
            else if ( config('ra-auth.login_strategy') == 'jwt' ) {
                return Response::error('Logged users are not allowed.');
            }
        }

        return $next($request);
    }
}
