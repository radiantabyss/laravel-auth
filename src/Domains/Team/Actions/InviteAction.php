<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Core\MailSender;
use RA\Auth\Services\ClassName;
use RA\Auth\Domains\Team\Mail\InviteMail;

class InviteAction extends Action
{
    public function run($id) {
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('Team\InviteValidator')::run($data, $id);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //save to db
        $data = ClassName::Transformer('Team\InviteTransformer')::run($data, $id);

        $item = ClassName::Model('Team')::find($id);

        $invites = [];
        foreach ( $data as $data_item ) {
            $invite = ClassName::Model('TeamInvite')::create($data_item);

            //send mail
            MailSender::run(InviteMail::class, $invite->email, [
                'team' => $item,
                'invite' => $invite,
            ]);

            $invites[] = $invite;
        }

        return Response::success(compact('invites'));
    }
}
