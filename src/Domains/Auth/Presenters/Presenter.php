<?php
namespace RA\Auth\Domains\Auth\Presenters;

use RA\Auth\Services\ClassName;

class Presenter
{
    public static function run($item, $team_id = null) {
        //load meta
        $item->loadMeta();

        //remove unwanted user fields
        unset($item->password);
        unset($item->created_at);
        unset($item->updated_at);

        //load team and role
        $team_user = ClassName::Model('UserTeamMember')::where('user_id', $item->id)
            ->where(function($query) use($team_id) {
                if ( $team_id ) {
                    $query->where('team_id', $team_id);
                }
            })
            ->orderBy('id')
            ->first();

        $item->team_id = $team_user->team_id;
        $item->role = $team_user->role;

        return $item;
    }
}
