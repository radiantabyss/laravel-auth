<?php
namespace Lumi\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use Lumi\Core\Response;
use Lumi\Core\MailSender;
use Lumi\Auth\Services\ClassName;

class InviteAction extends Action
{
    public function run($team_id) {
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('Team\InviteValidator')::run($data, $team_id);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        $team = ClassName::Model('Team')::find($team_id);

        //save to db
        $invites_data = ClassName::Transformer('Team\InviteTransformer')::run($data, $team_id);
        $invites = [];
        
        foreach ( $invites_data as $invite_data ) {
            $invite = ClassName::Model('TeamInvite')::create($invite_data);

            //send mail
            MailSender::run(ClassName::Mail('Team\InviteMail')::class, $invite->email, [
                'team' => $team,
                'invite' => $invite,
            ]);

            $invites[] = $invite;
        }

        return Response::success(compact('invites'));
    }
}
