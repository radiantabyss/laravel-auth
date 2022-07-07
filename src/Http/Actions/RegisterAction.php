<?php
namespace RA\Auth\Http\Actions;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Action;
use App\Core\MailSender;
use App\Core\Response;
use RA\Auth\Models\User as Model;
use RA\Auth\Models\UserMeta as UserMetaModel;
use RA\Auth\Presenters\UserPresenter as Presenter;
use RA\Auth\Presenters\JwtPresenter;
use RA\Auth\Transformers\RegisterTransformer as Transformer;
use RA\Auth\Validators\RegisterValidator as Validator;
use RA\Auth\Mail\ActivateAccountMail;
use RA\Auth\Services\Jwt;

class RegisterAction extends Action
{
    public function run(Request $request) {
        $data = $request->all();

        //validate
        $validation = Validator::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //transform data and insert
        $data = Transformer::run($data);
        $meta = $data['meta'] ?? [];
        unset($data['meta']);

        $item = Model::create($data);

        //insert meta
        foreach ( $meta as $key => $value ) {
            UserMetaModel::create([
                'user_id' => $item->id,
                'key' => $key,
                'value' => $value,
            ]);
        }

        //create jwt token
        $jwt_token = env('RA_AUTH_LOGIN_STRATEGY') == 'jwt' ? Jwt::generate(JwtPresenter::run(clone $item)) : '';

        //get redirect
        $redirect = env('RA_AUTH_LOGIN_STRATEGY') == 'session' ? config('ra-auth.redirect_after_register') : '';

        //login
        if ( !env('RA_AUTH_ACTIVATION_REQUIRED') && env('RA_AUTH_LOGIN_STRATEGY') == 'session' ) {
            \Auth::login($item);
            $redirect = config('ra-auth.redirect_after_login');
        }

        //format
        $item = Presenter::run($item);

        //send activation mail
        MailSender::send(ActivateAccountMail::class, $item->email, $item);

        return Response::success(compact('item', 'jwt_token', 'redirect'));
    }
}
