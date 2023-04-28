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

        $items = [];
        foreach ( $data as $data_item ) {
            $item = ClassName::Model('UserInvite')::create($data_item);

            //send mail
            MailSender::run(InviteMail::class, $item->email, [
                'team' => \Auth::user()->team,
                'invite' => $item,
            ]);

            $items[] = $item;
        }

        return Response::success(compact('items'));
    }
}
