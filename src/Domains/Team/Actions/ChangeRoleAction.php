<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Response;
use RA\Auth\Services\ClassName;

class ChangeRoleAction extends Action
{
    public function run($team_id) {
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('Team\ChangeRoleValidator')::run($data, $team_id);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        ClassName::Model('TeamMember')::where('id', $data['id'])->update([
            'role' => $data['role']
        ]);

        return Response::success();
    }
}
