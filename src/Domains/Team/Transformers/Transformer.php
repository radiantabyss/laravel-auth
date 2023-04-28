<?php
namespace RA\Auth\Domains\Team\Transformers;

class Transformer
{
    public static function run($data, $id = null) {
        if ( !$id ) {
            $data['uuid'] = \Str::uuid();
            $data['user_id'] = \Auth::user()->id;
        }

        return $data;
    }
}
