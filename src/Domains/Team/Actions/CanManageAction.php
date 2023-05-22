<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

class CanManageAction extends Action
{
    public function run($id) {
        $can_manage = ClassName::Model('TeamMember')::where('team_id', $id)
            ->where('user_id', \Auth::user()->id)
            ->where('role', 'owner')
            ->exists();

        return Response::success(compact('can_manage'));
    }
}
