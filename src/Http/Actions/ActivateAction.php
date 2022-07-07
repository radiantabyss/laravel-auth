<?php
namespace RA\Auth\Http\Actions;

use Illuminate\Routing\Controller as Action;
use App\Core\Response;
use RA\Auth\Models\User as Model;
use RA\Auth\Presenters\UserPresenter as Presenter;
use RA\Auth\Presenters\JwtPresenter;
use RA\Auth\Validators\ActivateValidator as Validator;
use RA\Auth\Services\Jwt;

class ActivateAction extends Action
{
    public function run($id, $code) {
        $validation = Validator::run(compact('id', 'code'));
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        $item = Model::find($id);
        $item->update([
            'activation_code' => null
        ]);
        $item = Presenter::run($item);

        //create jwt token
        $jwt_token = env('RA_AUTH_LOGIN_STRATEGY') == 'jwt' ? Jwt::generate(JwtPresenter::run(clone $item)) : '';

        return Response::success(compact('item', 'jwt_token'));
    }
}
