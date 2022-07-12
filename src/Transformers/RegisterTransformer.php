<?php
namespace RA\Auth\Transformers;

class RegisterTransformer
{
    public static function run($data) {
        $data['uuid'] = \Str::uuid();
        $data['type'] = config('ra-auth.default_user_type');
        $data['password'] = \Hash::make($data['password']);
        $data['activation_code'] = \Str::random(10);

        return $data;
    }
}
