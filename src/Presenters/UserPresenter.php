<?php
namespace RA\Auth\Presenters;

class UserPresenter
{
    public static function run($item) {
        //load meta
        $item->loadMeta();

        //remove unwanted user fields
        unset($item->password);
        unset($item->activation_code);
        unset($item->reset_code);
        unset($item->reset_code_date);
        unset($item->last_login_at);
        unset($item->created_at);
        unset($item->updated_at);
        unset($item->deleted_at);

        return $item;
    }
}
