<?php
namespace Lumi\Auth\Domains\User\Transformers;

class RegisterTransformer
{
    public static function run($data) {
        $data['uuid'] = \Str::uuid();
        $data['type'] = 'user';
        $data['password'] = \Hash::make($data['password']);

        return $data;
    }
}
