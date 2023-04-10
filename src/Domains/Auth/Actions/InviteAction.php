<?php
namespace RA\Auth\Domains\Auth\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Core\MailSender;
use RA\Auth\Services\ClassName;
use RA\Auth\Domains\Team\Mail\InviteMail;

class InviteAction extends Action
{
    public function run() {
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('InviteValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //save to db
        $data = ClassName::Transformer('InviteTransformer')::run($data);
        $invite = ClassName::Model('UserInvite')::create($data);

        //get team
        $team = ClassName::Model('UserTeam')::find($data['team_id']);

        //send mail
        MailSender::run(InviteMail::class, $data['email'], compact('team', 'invite'));

        return Response::success();
    }
}
