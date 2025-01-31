<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Response;
use RA\Auth\Services\ClassName;

class EditAction extends Action
{
    public function run($team_id) {
        $item = ClassName::Model('Team')::select('team.*')
            ->leftJoin('team_member', 'team_member.team_id', '=', 'team.id')
            ->where('team.id', $team_id)
            ->where('team_member.user_id', \Auth::user()->id)
            ->first();

        if ( !$item ) {
            return Response::error('Team not found.');
        }

        $item = ClassName::Presenter('Team\EditPresenter')::run($item);

        return Response::success(compact('item'));
    }
}
