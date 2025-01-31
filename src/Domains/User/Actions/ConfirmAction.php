<?php
namespace RA\Auth\Domains\User\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Response;
use RA\Auth\Services\Jwt;
use RA\Auth\Services\ClassName;

class ConfirmAction extends Action
{
    public function run() {
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('User\ConfirmValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //delete confirmation code
        ClassName::Model('UserCode')::where('user_id', $id)
            ->where('type', 'confirm')
            ->delete();

        $item = ClassName::Model('User')::find($id);

        //create jwt token
        $item = ClassName::Presenter('User\Presenter')::run($item);
        $jwt_token = Jwt::generate(ClassName::Presenter('User\JwtPresenter')::run(clone $item));

        //log event
        $item->log('confirmed_account', 'Confirmed account');

        return Response::success(compact('item', 'jwt_token'));
    }
}
