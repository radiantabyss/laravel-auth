<?php
namespace RA\Auth\Domains\Auth\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

class UpdateTeamAction extends Action
{
    public function run($team_id) {
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('TeamValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //update db
        $data = ClassName::Transformer('TeamTransformer')::run($data);
        ClassName::Model('UserTeam')::where('id', $team_id)->update($data);

        //format for return
        $team = ClassName::Model('UserTeam')::find($team_id);
        $team = ClassName::Presenter('TeamPresenter')::run($team);

        return Response::success(compact('team'));
    }
}
