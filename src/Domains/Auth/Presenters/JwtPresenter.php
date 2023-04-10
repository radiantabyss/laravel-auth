<?php
namespace RA\Auth\Domains\Auth\Presenters;

use RA\Auth\Services\ClassName;

class JwtPresenter
{
    public static function run($item, $team_id = null) {
        return [
            'id' => $item->id,
            'uuid' => $item->uuid,
            'team_id' => $item->team_id,
        ];
    }
}
