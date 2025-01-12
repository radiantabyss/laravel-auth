<?php
namespace RA\Auth\Services;

use Firebase\JWT\JWT as FirebaseJwt;

class Jwt
{
    public static function generate($payload) {
        if ( !config('jwt.secret') ) {
            throw 'JWT Secret cannot be null.';
        }

        return FirebaseJwt::encode($payload, config('jwt.secret'), config('jwt.algorithm'));
    }

    public static function validate($payload) {
        try {
            $token = FirebaseJwt::decode($payload, config('jwt.secret'), [config('jwt.algorithm')]);
        }
        catch (\Exception $e) {
            return false;
        }

        return $token;
    }
}
