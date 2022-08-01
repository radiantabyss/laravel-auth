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

        //transform data
        $data = ClassName::PatchTransformer()::run($data);

        //get meta
        $meta = [];
        if ( isset($data['meta']) ) {
            $meta = $data['meta'];
            unset($data['meta']);
        }

        //update
        ClassName::Model()::where('id', $item->id)->update($data);

        //update meta
        foreach ( $meta as $key => $value ) {
            ClassName::MetaModel()::updateOrCreate([
                'user_id' => $item->id,
                'key' => $key,
            ], [
                'user_id' => $item->id,
                'key' => $key,
                'value' => $value,
            ]);
        }

        //get for return
        $item = ClassName::Model()::where('id', $item->id)->first();
        $item = ClassName::Presenter()::run($item);

        return Response::success(compact('item'));
    }
}
