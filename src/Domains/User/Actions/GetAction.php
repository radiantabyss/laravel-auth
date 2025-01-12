<?php
namespace RA\Auth\Domains\User\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;

class GetAction extends Action
{
    public function run() {
        $item = \Auth::user();

        if ( !$item ) {
            return Response::error('User not logged.');
        }

        return Response::success(compact('item'));
    }
}
