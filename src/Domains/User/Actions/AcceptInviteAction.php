<?php
namespace RA\Auth\Domains\User\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

class AcceptInviteAction extends Action
{
    public function run($email, $code) {
        //validate request
        $validation = ClassName::Validator('User\AcceptInviteValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        $data = ClassName::Transformer('User\AcceptInviteTransformer')::run($data);

        //check if user is logged
        if ( \Auth::check() ) {
            $item = \Auth::user();
        }
        else {
            $item = ClassName::Model('User')::where('email', $data['email'])->first();

            //check if user exists
            if ( !$item ) {
                $meta = $data['meta'] ?? [];
                unset($data['meta']);

                //create user
                $item = ClassName::Model('User')::create($data);

                //insert meta
                foreach ( $meta as $key => $value ) {
                    ClassName::Model('UserMeta')::create([
                        'user_id' => $item->id,
                        'key' => $key,
                        'value' => $value,
                    ]);
                }
            }
        }

        $invite = ClassName::Model('TeamInvite')::where('code', $data['code'])->first();

        //insert user in team
        ClassName::Model('TeamMember')::create([
            'team_id' => $invite->team_id,
            'user_id' => $item->id,
            'role' => $invite->role,
        ]);

        //generate jwt token
        $item = ClassName::Presenter('User\Presenter')::run(\Auth::user(), $invite->team_id);
        $jwt_token = Jwt::generate(ClassName::Presenter('User\JwtPresenter')::run(clone $item));

        //log event
        $item->log('joined_team', 'Joined team '.$invite->team_id);

        //delete the invite
        $invite->delete();

        return Response::success(compact('item', 'jwt_token'));
    }
}
