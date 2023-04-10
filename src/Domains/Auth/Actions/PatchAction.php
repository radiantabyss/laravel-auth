<?php
namespace RA\Auth\Domains\Auth\Actions;

use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Services\ClassName;

class PatchAction extends Action
{
    public function run() {
        $item = \Auth::user();
        $data = \Request::all();

        //validate request
        $validation = ClassName::Validator('PatchValidator')::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        //transform data
        $data = ClassName::Transformer('PatchTransformer')::run($data);

        //get meta
        $meta = [];
        if ( isset($data['meta']) ) {
            $meta = $data['meta'];
            unset($data['meta']);
        }

        //update
        ClassName::Model('User')::where('id', $item->id)->update($data);

        //update meta
        foreach ( $meta as $key => $value ) {
            ClassName::Model('UserMeta')::updateOrCreate([
                'user_id' => $item->id,
                'key' => $key,
            ], [
                'user_id' => $item->id,
                'key' => $key,
                'value' => $value,
            ]);
        }

        //get for return
        $item = ClassName::Model('User')::where('id', $item->id)->first();
        $item = ClassName::Presenter('Presenter')::run($item);

        return Response::success(compact('item'));
    }
}
