<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

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
