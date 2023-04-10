<?php
namespace RA\Auth\Domains\Auth\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;
use RA\Auth\Services\Jwt;

class ResetPasswordAction extends Action
{
    public function run() {
        $data = \Request::all();

        //validate
        $validation = ClassName::Validator('ResetPasswordValidator')::run($data);
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

        //log event
        $item->log('reset_password', 'Reset password');

        //create jwt token
        $item = ClassName::Presenter('Presenter')::run($item);
        $jwt_token = Jwt::generate(ClassName::Presenter('JwtPresenter')::run(clone $item));

        return Response::success(compact('item', 'jwt_token'));
    }
}
