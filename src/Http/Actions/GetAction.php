<?php
namespace RA\Auth\Http\Actions;

use Illuminate\Routing\Controller as Action;
use App\Core\Response;
use RA\Auth\Presenters\UserPresenter as Presenter;

class GetAction extends Action
{
    public function run() {
        $item = \Auth::user();

        if ( !$item ) {
            return Response::error('User not logged.');
        }

        $item = Presenter::run($item);

        return Response::success(compact('item'));
    }
}
