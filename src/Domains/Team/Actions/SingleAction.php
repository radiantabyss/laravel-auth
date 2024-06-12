<?php
namespace Lumi\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use Lumi\Core\Response;
use Lumi\Auth\Services\ClassName;

class SingleAction extends Action
{
    public function run($team_id) {
        //check if is user is in team
        $exists = ClassName::Model('TeamMember')::where('team_id', $team_id)
            ->where('user_id', \Auth::user()->id)
            ->exists();

        if ( !$exists ) {
            return Response::error('Team not found.');
        }

        $item = ClassName::Model('Team')::find($team_id);

        if ( !$item ) {
            return Response::error('Team not found.');
        }

        $item = ClassName::Presenter('Team\Presenter')::run($item);

        return Response::success(compact('item'));
    }
}
