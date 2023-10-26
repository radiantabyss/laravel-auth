<?php
namespace Lumi\Auth\Domains\User\Transformers;

class AcceptInviteTransformer
{
    public static function run($data) {
        if ( !\Auth::check() ) {
            $data['uuid'] = \Str::uuid();
            $data['type'] = 'user';
            $data['password'] = \Hash::make($data['password']);
        }

        return $data;
    }
}
