<?php
namespace RA\Auth\Domains\Auth\Actions;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;
use RA\Auth\Services\Jwt;

class LoginAction extends Action
{
    public function run(Request $request) {
        $data = $request->all();

        //validate request data
        $validation = ClassName::Validator('LoginValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        $item = ClassName::Model('User')::where('email', trim($data['email']))->first();
        $item->log('login', date('Y-m-d H:i:s'));

        $item = ClassName::Presenter('Presenter')::run($item);

        //generate jwt token
        $jwt_token = Jwt::generate(ClassName::Presenter('JwtPresenter')::run(clone $item));

        return Response::success(compact('item', 'jwt_token'));
    }
}
