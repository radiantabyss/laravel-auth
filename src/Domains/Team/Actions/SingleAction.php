<?php
namespace Lumi\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use Lumi\Core\Response;
use Lumi\Auth\Services\ClassName;

class SingleAction extends Action
{
    public function run($id) {
        $item = ClassName::Model('Team')::find($id);

        if ( !$item ) {
            return Response::error('Team not found.');
        }

        $item = ClassName::Presenter('Team\Presenter')::run($item);

        return Response::success(compact('item'));
    }
}
