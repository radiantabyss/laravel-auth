<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Response;
use RA\Auth\Services\ClassName;
use RA\Auth\Services\Jwt;

class SwitchAction extends Action
{
    public function run($team_id) {
        //validate request
        $validation = ClassName::Validator('Team\SwitchValidator')::run($team_id);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //regenerate jwt token
        $user = ClassName::Presenter('User\Presenter')::run(\Auth::user(), $team_id);
        $jwt_token = Jwt::generate(ClassName::Presenter('User\JwtPresenter')::run(clone $user));

        return Response::success(compact('user', 'jwt_token'));
    }
}
