<?php
namespace RA\Auth\Presenters;

class UsersPresenter
{
    public static function run($items) {
        //load meta
        $items = ClassName::Model()::loadMetaForMany($items);

        //remove unwanted user fields
        foreach ( $items as $item ) {
            unset($item->password);
            unset($item->activation_code);
            unset($item->reset_code);
            unset($item->last_login_at);
            unset($item->created_at);
            unset($item->updated_at);
        }

        return $items;
    }
}
