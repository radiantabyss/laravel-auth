<?php
namespace RA\Auth\Http\Actions;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Action;
use App\Core\Response;
use RA\Auth\Models\User as Model;
use RA\Auth\Presenters\UserPresenter as Presenter;
use RA\Auth\Transformers\PatchTransformer as Transformer;

class PatchAction extends Action
{
    public function run(Request $request) {
        $item = \Auth::user();
        $data = $request->all();

        //update
        $data = Transformer::run($data);
        Model::where('id', $item->id)->update($data);
        $item = Model::where('id', $item->id)->first();
        $item = Presenter::run($item);

        return Response::success(compact('item'));
    }
}
