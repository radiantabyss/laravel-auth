<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

class ChangeRoleAction extends Action
{
    public function run() {
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('Team\ChangeRoleValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        ClassName::Model('UserTeamMember')::where('id', $data['id'])->update([
            'role' => $data['role']
        ]);

        return Response::success();
    }
}
