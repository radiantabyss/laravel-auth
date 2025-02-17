<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Response;
use RA\Auth\Services\ClassName;

class DeleteMemberAction extends Action
{
    public function run($team_id, $id) {
        $item = ClassName::Model('TeamMember')::find($id);

        //validate request
        $validation = ClassName::Validator('Team\DeleteMemberValidator')::run($item);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        $item->delete();

        return Response::success();
    }
}
