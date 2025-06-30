<?php
namespace RA\Auth\Domains\User\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Response;

class GetAction extends Action
{
    public function run() {
        $item = \Auth::user();
        return Response::success(compact('item'));
    }
}
