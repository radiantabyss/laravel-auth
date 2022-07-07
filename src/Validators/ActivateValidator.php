<?php
namespace RA\Auth\Validators;

use RA\Auth\Models\User as Model;

class ActivateValidator
{
    public static function run($data) {
        //validate request params
        $validator = \Validator::make($data, [
            'id' => 'required',
            'code' => 'required',
        ], [
            'id.required' => 'ID is required',
            'code.required' => 'Code is required',
        ]);

        if ( $validator->fails() ) {
            return $validator->messages();
        }

        $item = Model::where('id', $data['id'])->where('activation_code', $data['code'])->first();

        if ( !$item ) {
            return 'User has been activated or doesn\'t exist.';
        }

        return true;
    }
}
