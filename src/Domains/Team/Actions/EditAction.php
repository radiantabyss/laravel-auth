<?php
namespace RA\Auth\Domains\Team\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

class EditAction extends Action
{
    public function run($id) {
        $item = ClassName::Model('UserTeam')::find($id);

        if ( !$item ) {
            return Response::error('Team not found.');
        }

        if ( \Gate::allows('manage-team', $id) ) {
            return Response::error('Sorry, you can\'t edit this team.');
        }

        $item = ClassName::Presenter('Team\EditPresenter')::run($item);

        return Response::success(compact('item'));
    }
}
