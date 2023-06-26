<?php
namespace RA\Auth\Domains\User\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;
use RA\Auth\Services\Jwt;

class ResetPasswordAction extends Action
{
    public function run() {
        $data = \Request::all();

        //validate
        $validation = ClassName::Validator('User\ResetPasswordValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        $code = ClassName::Model('UserCode')::where('type', 'reset_password')
            ->where('code', $data['code'])
            ->first();

        $item = ClassName::Model('User')::find($code->user_id);
        $item->update([
            'password' => \Hash::make($data['password']),
        ]);

        //create jwt token
        $item = ClassName::Presenter('User\Presenter')::run($item);
        $jwt_token = Jwt::generate(ClassName::Presenter('User\JwtPresenter')::run(clone $item));

        //log event
        $item->log('reset_password', 'Reset password');

        return Response::success(compact('item', 'jwt_token'));
    }
}
