<?php
namespace RA\Auth\Domains\Auth\Presenters;

class TeamPresenter
{
    public static function run($item) {
        $item->loadMeta();

        return $item;
    }
}
