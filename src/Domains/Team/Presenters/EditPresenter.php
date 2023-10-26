<?php
namespace Lumi\Auth\Domains\Team\Presenters;

use Lumi\Auth\Services\ClassName;

class EditPresenter
{
    public static function run($item) {
        $item->loadMeta();

        return $item;
    }
}
