<?php
namespace RA\Auth\Domains\Auth\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\MailSender;
use RA\Core\Response;
use RA\Auth\Domains\Auth\Mail\WelcomeMail;
use RA\Auth\Services\ClassName;
use RA\Auth\Services\Jwt;

class RegisterAction extends Action
{
    public function run() {
        $data = \Request::all();

        //validate
        $validation = ClassName::Validator('RegisterValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //transform data and insert
        $data = ClassName::Transformers('RegisterTransformer')::run($data);
        $meta = $data['meta'] ?? [];
        unset($data['meta']);

        $item = ClassName::Model('User')::create($data);

        //insert meta
        foreach ( $meta as $key => $value ) {
            ClassName::Model('UserMeta')::create([
                'user_id' => $item->id,
                'key' => $key,
                'value' => $value,
            ]);
        }

        //create default team
        ClassName::Model('UserTeam')::create([
            'user_id' => $item->id,
            'name' => 'My Team',
        ]);

        //insert user in own team
        ClassName::Model('UserTeamMember')::create([
            'user_id' => $item->id,
            'role' => 'owner',
        ]);

        //create confirmation code
        if ( config('ra-auth.activation_required') ) {
            $code = ClassName::Model('UserCode')::create([
                'user_id' => $item->id,
                'type' => 'confirm',
                'code' => \Str::random(30),
                'expires_at' => date('Y-m-d H:i:s', strtotime('+1 day')),
            ]);
        }
        else {
            $code = null;
        }

        //send welcome mail
        if ( config('ra-auth.send_welcome_mail') ) {
            MailSender::send(WelcomeMail::class, $item->email, compact('item', 'code'));
        }

        //format
        $item = ClassName::Presenter('Presenter')::run($item);

        //create jwt token
        $jwt_token = Jwt::generate(ClassName::Presenter('JwtPresenter')::run(clone $item));

        return Response::success(compact('item', 'jwt_token'));
    }
}
