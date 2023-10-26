<?php
namespace Lumi\Auth\Domains\User\Actions;

use Illuminate\Routing\Controller as Action;
use Lumi\Core\Response;
use Lumi\Auth\Services\ClassName;
use Lumi\Auth\Services\Jwt;

class LoginAction extends Action
{
    public function run() {
        $data = \Request::all();

        //validate request data
        $validation = ClassName::Validator('User\LoginValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        $item = ClassName::Model('User')::where('email', trim($data['email']))->first();
        $item = ClassName::Presenter('User\Presenter')::run($item);

        //generate jwt token
        $jwt_token = Jwt::generate(ClassName::Presenter('User\JwtPresenter')::run(clone $item));

        //log event
        $item->log('login', date('Y-m-d H:i:s'));

        return Response::success(compact('item', 'jwt_token'));
    }
}
