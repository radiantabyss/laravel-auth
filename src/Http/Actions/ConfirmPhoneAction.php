<?php
namespace RA\Auth\Http\Actions;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as Action;
use RA\Core\Response;
use RA\Auth\Validators\ValidatePhoneValidator as Validator;
use RA\Auth\Services\ClassName;

class ConfirmPhoneAction extends Action
{
    public function run(Request $request) {
        $item = \Auth::user();
        $data = $request->all();

        //validate request data
        $validation = Validator::run($data);
        if ( $validation !== true ) {
            return Response::error($validation);
        }

        ClassName::MetaModel()::where('user_id', $item->id)->where('key', 'phone_is_valid')->update([
            'phone_is_valid' => true,
        ]);

        ClassName::MetaModel()::where('user_id', $item->id)->where('key', 'phone_is_valid')->update([
            'phone_validation_code' => null,
        ]);

        return Response::success();
    }
}
