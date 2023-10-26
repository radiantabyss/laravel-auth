<?php
namespace Lumi\Auth\Domains\User\Actions;

use Illuminate\Routing\Controller as Action;
use Lumi\Core\MailSender;
use Lumi\Core\Response;
use Lumi\Auth\Services\ClassName;
use Lumi\Auth\Services\Jwt;

class RegisterAction extends Action
{
    public function run() {
        $data = \Request::all();

        //validate
        $validation = ClassName::Validator('User\RegisterValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //transform data and insert
        $data = ClassName::Transformer('User\RegisterTransformer')::run($data);
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
        $team = ClassName::Model('Team')::create([
            'user_id' => $item->id,
            'uuid' => \Str::uuid(),
            'name' => 'My Team',
        ]);

        //insert user in own team
        ClassName::Model('TeamMember')::create([
            'team_id' => $team->id,
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
            MailSender::send(ClassName::Mail('User\WelcomeMail'), $item->email, compact('item', 'code'));
        }

        //format
        $item = ClassName::Presenter('User\Presenter')::run($item);

        //create jwt token
        $jwt_token = Jwt::generate(ClassName::Presenter('User\JwtPresenter')::run(clone $item));

        return Response::success(compact('item', 'jwt_token'));
    }
}
