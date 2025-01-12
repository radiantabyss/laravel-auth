<?php
namespace RA\Auth\Domains\Team\Presenters;

use RA\Auth\Services\ClassName;

class EditPresenter
{
    public static function run($item) {
        $item->loadMeta();

        return $item;
    }
}
