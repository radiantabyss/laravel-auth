<?php
namespace RA\Auth\Services;

use RA\Auth\Models\User as Model;
use RA\Auth\Presenters\UserPresenter as Presenter;

class Login
{
    private static $request;

    public static function run($request) {
        self::$request = $request;

        $strategy = env('RA_AUTH_LOGIN_STRATEGY');
        return self::$strategy();
    }

    private static function session() {
        if ( !\Auth::check() ) {
            return false;
        }

        $user = Model::find(\Auth::user()->id);
        $user = Presenter::run($user);
        \Auth::setUser($user);

        return true;
    }

    private static function jwt() {
        //get jwt token from request
        $payload = self::$request->get(config('jwt.input'));

        //remove jwt token from request
        self::$request->query->remove(config('jwt.input'));

        if ( !$payload ) {
            return 'JWT Token is required.';
        }

        //validate token
        $token = Jwt::validate($payload);
        if ( $token === false ) {
            return 'JWT Token is invalid.';
        }

        //get user from token
        $user = Model::where('id', $token->id)
            ->where('uuid', $token->uuid)
            ->first();

        //check
        if ( !$user ) {
            return 'JWT Token is invalid.';
        }

        //format
        $user = Presenter::run($user);
        \Auth::setUser($user);

        return true;
    }
}
