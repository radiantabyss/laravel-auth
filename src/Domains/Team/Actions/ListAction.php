<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Response;
use RA\Auth\Services\ClassName;

class ListAction extends Action
{
    public function run() {
        $items = ClassName::Model('Team')::select('team.*', 'team_member.role', 'team_member.created_at as joined_at')
            ->leftJoin('team_member', 'team_member.team_id', '=', 'team.id')
            ->where('team_member.user_id', \Auth::user()->id)
            ->get();

        $items = ClassName::Presenter('Team\ListPresenter')::run($items);

        return Response::success(compact('items'));
    }
}
