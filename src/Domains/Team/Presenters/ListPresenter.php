<?php
namespace RA\Auth\Domains\Team\Presenters;

use RA\Auth\Services\ClassName;

class ListPresenter
{
    public static function run($items) {
        $items = ClassName::Model('Team')::loadMetaForMany($items, ['image_path']);

        return $items;
    }
}
