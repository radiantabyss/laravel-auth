<?php
namespace RA\Auth\Domains\User\Transformers;

use RA\Auth\Services\ClassName;

class PatchTransformer
{
    private static $allowed_fields = [
        'username', 'email', 'current_password', 'password',
    ];

    private static $allowed_meta_fields = [
        'public_name', 'first_name', 'last_name', 'profile_image_path',
    ];

    public static function run($data) {
        $data = self::filterFields($data);
        $data = self::filterMetaFields($data);

        //update email
        if ( isset($data['email']) && $data['email'] != \Auth::user()->email ) {
            $exists = ClassName::Model('User')::where('email', $data['email'])->exists();
            if ( $exists ) {
                return 'An user with this email already exists.';
            }
        }

        //update password
        if ( isset($data['password']) ) {
            $data['password'] = \Hash::make($data['password']);
            unset($data['current_password']);
        }

        return $data;
    }

    private static function filterFields($data) {
        foreach ( $data as $key => $value ) {
            if ( $key != 'meta' && !in_array($key, self::$allowed_fields) ) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    private static function filterMetaFields($data) {
        if ( !isset($data['meta']) ) {
            return $data;
        }

        $meta = $data['meta'];

        foreach ( $meta as $key => $value ) {
            if ( !in_array($key, self::$allowed_meta_fields) ) {
                unset($meta[$key]);
            }
        }

        $data['meta'] = $meta;

        return $data;
    }
}
