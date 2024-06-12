<?php
namespace Lumi\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use Lumi\Core\Response;
use Lumi\Auth\Services\ClassName;

class DeleteInviteAction extends Action
{
    public function run($team_id, $id) {
        $item = ClassName::Model('TeamInvite')::find($id);

        if ( !$item ) {
            return 'Invite not found.';
        }

        $item->delete();

        return Response::success();
    }
}
