<?php
namespace RA\Auth\Services;

class SetUser
{
    private static $request;

    public static function run($request) {
        self::$request = $request;

        $strategy = config('ra-auth.login_strategy');
        return self::$strategy();
    }

    private static function session() {
        if ( !\Auth::check() ) {
            return false;
        }

        $user = ClassName::Model()::find(\Auth::user()->id);
        $user = ClassName::Presenter()::run($user);
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
        $user = ClassName::Model()::where('id', $token->id)
            ->where('uuid', $token->uuid)
            ->first();

        //check
        if ( !$user ) {
            return 'JWT Token is invalid.';
        }

        //format
        $user = ClassName::Presenter()::run($user);
        \Auth::setUser($user);

        return true;
    }
}
