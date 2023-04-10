<?php
namespace RA\Auth\Domains\Auth\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;
use RA\Auth\Services\Jwt;

class SwitchTeamAction extends Action
{
    public function run($team_id) {
        //validate request
        $validation = ClassName::Validator('SwitchTeamValidator')::run($team_id);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //regenerate jwt token
        $item = ClassName::Presenter('Presenter')::run(\Auth::user(), $team_id);
        $jwt_token = Jwt::generate(ClassName::Presenter('JwtPresenter')::run(clone $item));

        return Response::success(compact('item', 'jwt_token'));
    }
}
