<?php
namespace RA\Auth\Domains\Auth\Transformers;

use RA\Auth\Services\ClassName;

class PatchTransformer
{
    private static $allowed_fields = [
        'username', 'email', 'password',
        'first_name', 'last_name', 'image_url',
    ];

    private static $meta_fields = ['first_name', 'last_name', 'image_url'];

    public static function run($data) {
        $meta = [];

        //filter fields
        foreach ( $data as $key => $value ) {
            if ( !in_array($key, self::$allowed_fields) ) {
                unset($data[$key]);
            }

            if ( in_array($key, self::$meta_fields) ) {
                $meta[$key] = $value;
                unset($data[$key]);
            }
        }

        //update email
        if ( isset($data['email']) && $data['email'] != \Auth::user()->email ) {
            $exists = ClassName::Model('User')::where('email', $data['email'])->exists();
            if ( $exists ) {
                return 'An user with this email already exists.';
            }
        }

        //update password
        if ( isset($data['password']) ) {
            $data['password'] = app('hash')->make($data['password']);
        }

        return $data;
    }
}
