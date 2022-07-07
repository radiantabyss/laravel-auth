<?php
namespace RA\Auth\Http\Middleware;

use App\Core\Response;
use RA\Auth\Models\User as Model;
use RA\Auth\Presenters\UserPresenter as Presenter;
use RA\Auth\Services\Jwt;

class AuthMiddleware
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
        if ( env('RA_AUTH_LOGIN_STRATEGY') == 'session' ) {
            if ( !\Auth::check() ) {
                if ( !in_array($_SERVER['REQUEST_URI'], ['/login', '/logout']) ) {
                    session(['redirect' => $_SERVER['REQUEST_URI']]);
                }

                return redirect(config('ra-auth.default_redirect_if_not_logged'));
            }

            $user = Model::find(\Auth::user()->id);
            $user = Presenter::run($user);
            \Auth::setUser($user);
        }
        else if ( env('RA_AUTH_LOGIN_STRATEGY') == 'jwt' ) {
            $payload = $request->get(config('jwt.input'));
            if ( !$payload ) {
                return Response::error('JWT Token is required.');
            }

            //validate token
            $token = Jwt::validate($payload);
            if ( $token === false ) {
                return Response::error('JWT Token is invalid.');
            }

            //get user from token
            $user = Model::where('id', $token->id)
                ->where('uuid', $token->uuid)
                ->first();

            //check
            if ( !$user ) {
                return Response::error('JWT Token is invalid.');
            }

            //format
            $user = Presenter::run($user);
            \Auth::setUser($user);

            $request->query->remove(env('JWT_INPUT'));
        }

        return $next($request);
    }
}
