<?php
namespace Lumi\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use Lumi\Core\Response;

class CanManageAction extends Action
{
    public function run($id) {
        return Response::success(\Gate::allows('manage-team', $id));
    }
}
