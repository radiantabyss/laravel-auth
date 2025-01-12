<?php
namespace RA\Auth\Domains\Team\Transformers;

class Transformer
{
    public static function run($data, $team_id = null) {
        if ( !$team_id ) {
            $data['uuid'] = \Str::uuid();
            $data['created_by'] = \Auth::user()->id;
        }
        else {
            unset($data['uuid']);
            unset($data['created_by']);
        }

        return $data;
    }
}
