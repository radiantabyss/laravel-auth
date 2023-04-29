<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

class DeleteInviteAction extends Action
{
    public function run($id) {
        $item = ClassName::Model('UserInvite')::find($id);

        if ( !$item ) {
            return 'Invite not found.';
        }

        if ( \Gate::denies('manage-team', $item->team_id) ) {
            return 'Sorry, you can\'t delete this invite.';
        }

        $item->delete();

        return Response::success();
    }
}
