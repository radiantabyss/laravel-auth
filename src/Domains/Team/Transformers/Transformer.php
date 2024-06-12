<?php
namespace Lumi\Auth\Domains\Team\Transformers;

class Transformer
{
    public static function run($data, $team_id = null) {
        if ( !$team_id ) {
            $data['uuid'] = \Str::uuid();
            $data['user_id'] = \Auth::user()->id;
        }
        else {
            unset($data['uuid']);
            unset($data['user_id']);
        }

        return $data;
    }
}
