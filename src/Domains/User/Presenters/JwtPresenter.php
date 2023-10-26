<?php
namespace Lumi\Auth\Domains\User\Presenters;

use Lumi\Auth\Services\ClassName;

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
