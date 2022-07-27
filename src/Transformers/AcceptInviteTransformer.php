<?php
namespace RA\Auth\Transformers;

class AcceptInviteTransformer
{
    public static function run($data) {
        $data['uuid'] = \Str::uuid();
        $data['type'] = \Crypt::decryptString($data['code']);;
        $data['password'] = \Hash::make($data['password']);
        $data['activation_code'] = \Str::random(10);

        unset($data['code']);

        return $data;
    }
}
