<?php
namespace RA\Auth\Http\Middleware;

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
            return redirect('/');
        }

        return $next($request);
    }
}
