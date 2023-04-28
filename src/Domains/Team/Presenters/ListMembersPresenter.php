<?php
namespace RA\Auth\Domains\Team\Presenters;

use RA\Auth\Services\ClassName;

class ListMembersPresenter
{
    public static function run($items) {
        //load members
        $users = keyBy(ClassName::Model('User')::loadMetaForMany(pluck($items, 'user'), ['public_name']));

        foreach ( $items as $item ) {
            $item->user = $users[$item->user_id];
        }

        return $items;
    }
}
