<?php
namespace RA\Auth\Domains\User\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;
use RA\Auth\Services\Jwt;

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
        $item->log('login', date('Y-m-d H:i:s'));

        $item = ClassName::Presenter('User\Presenter')::run($item);

        //generate jwt token
        $jwt_token = Jwt::generate(ClassName::Presenter('User\JwtPresenter')::run(clone $item));

        return Response::success(compact('item', 'jwt_token'));
    }
}
