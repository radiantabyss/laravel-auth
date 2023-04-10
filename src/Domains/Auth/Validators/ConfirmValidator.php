<?php
namespace RA\Auth\Domains\Auth\Validators;

use RA\Auth\Models as Model;

class ConfirmValidator
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

        $item = ClassName::Model('User')::find($data['id']);

        if ( !$item ) {
            return 'User has been activated or doesn\'t exist.';
        }

        $code = ClassName::Model('UserCode')::where('user_id', $data['id'])
            ->where('type', 'confirm')
            ->first();

        if ( !$code ) {
            return 'User has been activated or doesn\'t exist.';
        }

        if ( $code->code != $data['code'] ) {
            return 'Code is not valid.';
        }

        if ( $code->expires_at < date('Y-m-d H:i:s') ) {
            return 'Code has expired.';
        }

        return true;
    }
}
