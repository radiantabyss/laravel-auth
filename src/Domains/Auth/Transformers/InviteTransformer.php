<?php
namespace RA\Auth\Domains\Auth\Transformers;

class InviteTransformer
{
    public static function run($data) {
        $data['code'] = \Str::random(30);
        $data['expires_at'] = date('Y-m-d H:i:s', strtotime('+2 hours'));

        return $data;
    }
}
