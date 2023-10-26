<?php
namespace Lumi\Auth\Domains\User\Presenters;

use Lumi\Auth\Services\ClassName;

class Presenter
{
    public static function run($item, $team_id = null) {
        //load meta
        $item->loadMeta();

        //remove unwanted user fields
        unset($item->password);
        unset($item->created_at);
        unset($item->updated_at);

        //load current or first team and role
        $team = ClassName::Model('Team')::select('team.*', 'team_member.role')
            ->leftJoin('team_member', 'team_member.team_id', '=', 'team.id')
            ->where('team_member.user_id', $item->id)
            ->where(function($query) use($team_id) {
                if ( $team_id ) {
                    $query->where('team.id', $team_id);
                }
            })
            ->orderBy('id')
            ->first();

        if ( $team ) {
            $team->loadMeta();
        }

        $item->team = $team;

        return $item;
    }
}
