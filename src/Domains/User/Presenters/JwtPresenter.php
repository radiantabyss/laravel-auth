<?php
namespace RA\Auth\Domains\User\Presenters;

use RA\Auth\Services\ClassName;

class JwtPresenter
{
    public static function run($item) {
        return [
            'id' => $item->id,
            'uuid' => $item->uuid,
            'team_id' => $item->team->id,
        ];
    }
}
