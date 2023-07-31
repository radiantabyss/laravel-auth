<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

class EditAction extends Action
{
    public function run($id) {
        $item = ClassName::Model('Team')::select('team.*', 'team_member.role', 'team_member.created_at as joined_at')
            ->leftJoin('team_member', 'team_member.team_id', '=', 'team.id')
            ->where('team.id', $id)
            ->where('team_member.user_id', \Auth::user()->id)
            ->first();

        if ( !$item ) {
            return Response::error('Team not found.');
        }

        if ( \Gate::denies('manage-team', $id) ) {
            return Response::error('Sorry, you can\'t edit this team.');
        }

        $item = ClassName::Presenter('Team\EditPresenter')::run($item);

        return Response::success(compact('item'));
    }
}
