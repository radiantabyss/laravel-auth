<?php
namespace RA\Auth\Http\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Presenters\JwtPresenter;
use RA\Auth\Validators\ActivateValidator as Validator;
use RA\Auth\Services\ClassName;
use RA\Auth\Services\Jwt;

class ActivateAction extends Action
{
    public function run($id, $code) {
        $validation = Validator::run(compact('id', 'code'));
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        $item = ClassName::Model()::find($id);
        $item->update([
            'activation_code' => null
        ]);

        $item = ClassName::Presenter()::run($item);

        //create jwt token
        $jwt_token = config('ra-auth.login_strategy') == 'jwt' ? Jwt::generate(JwtPresenter::run(clone $item)) : '';

        return Response::success(compact('item', 'jwt_token'));
    }
}
