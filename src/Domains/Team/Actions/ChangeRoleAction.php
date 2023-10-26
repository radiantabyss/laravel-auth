<?php
namespace Lumi\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use Lumi\Core\Response;
use Lumi\Auth\Services\ClassName;

class ChangeRoleAction extends Action
{
    public function run() {
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('Team\ChangeRoleValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        ClassName::Model('TeamMember')::where('id', $data['id'])->update([
            'role' => $data['role']
        ]);

        return Response::success();
    }
}
