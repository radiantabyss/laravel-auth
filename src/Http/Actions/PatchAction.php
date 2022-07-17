<?php
namespace RA\Auth\Http\Actions;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

class PatchAction extends Action
{
    public function run(Request $request) {
        $item = \Auth::user();
        $data = $request->all();

        //validate request
        $validation = ClassName::PatchValidator()::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //update
        $data = ClassName::PatchTransformer()::run($data);
        ClassName::Model()::where('id', $item->id)->update($data);

        //get for return
        $item = ClassName::Model()::where('id', $item->id)->first();
        $item = ClassName::Presenter()::run($item);

        return Response::success(compact('item'));
    }
}
