<?php
namespace RA\Auth\Http\Actions;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Presenters\JwtPresenter;
use RA\Auth\Services\ClassName;
use RA\Auth\Services\Jwt;

class AcceptInviteAction extends Action
{
    public function run(Request $request) {
        $data = $request->all();

        //validate request
        $validation = ClassName::AcceptInviteValidator()::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //create user
        $data = ClassName::AcceptInviteTransformer()::run($data);
        $item = ClassName::Model()::create($data);
        $item = ClassName::Presenter()::run($item);

        //create jwt token
        $jwt_token = config('ra-auth.login_strategy') == 'jwt' ? Jwt::generate(JwtPresenter::run(clone $item)) : '';

        return Response::success(compact('item', 'jwt_token'));
    }
}
