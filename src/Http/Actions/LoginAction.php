<?php
namespace RA\Auth\Http\Actions;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Action;
use App\Core\Response;
use RA\Auth\Models\User as Model;
use RA\Auth\Models\UserToken as UserTokenModel;
use RA\Auth\Presenters\UserPresenter as Presenter;
use RA\Auth\Presenters\JwtPresenter;
use RA\Auth\Validators\LoginValidator as Validator;
use RA\Auth\Services\Jwt;

class LoginAction extends Action
{
    public function run(Request $request) {
        $data = $request->all();

        //validate request data
        $validation = Validator::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        $item = Model::where('email', trim($data['email']))->first();
        $item->update([
            'last_login_at' => date('Y-m-d H:i:s'),
        ]);

        //handle login strategy
        $response = $this->handleLoginStrategy($item, $data);

        $item = Presenter::run($item);

        return Response::success(array_merge($response, compact('item')));
    }

    private function handleLoginStrategy($item, $data) {
        $jwt_token = $redirect = $remember_token = '';

        if ( env('RA_AUTH_LOGIN_STRATEGY') == 'session' ) {
            \Auth::login($item);

            $redirect = config('ra-auth.redirect_after_login');
            if ( session('redirect') && session('redirect') != '/login' ) {
                $redirect = session('redirect');
            }
        }
        else if ( env('RA_AUTH_LOGIN_STRATEGY') == 'jwt' ) {
            $jwt_token = Jwt::generate(JwtPresenter::run(clone $item));
        }

        //generate remember token
        if ( isset($data['remember']) && $data['remember'] ) {
            $remember_token = \Hash::make(\Str::random(15));
            UserTokenModel::create([
                'user_id' => $item->id,
                'token' => $remember_token,
            ]);
        }

        return compact('jwt_token', 'redirect', 'remember_token');
    }
}
