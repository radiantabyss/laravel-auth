<?php
namespace Lumi\Auth\Domains\Team\Presenters;

class Presenter
{
    public static function run($item) {
        $item->loadMeta(['image_path']);

        return $item;
    }
}
