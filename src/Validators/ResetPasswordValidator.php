<?php
namespace RA\Auth\Validators;

use RA\Auth\Services\ClassName;

class ResetPasswordValidator
{
    public static function run($data) {
        //validate request params
        $validator = \Validator::make($data, [
            'id' => 'required',
            'reset_code' => 'required',
            'password' => 'required',
        ], [
            'id.required' => 'User ID is required',
            'reset_code.required' => 'Reset Code is required',
            'password.required' => 'Password is required',
        ]);

        if ( $validator->fails() ) {
            return $validator->messages();
        }

        //check user
        $item = ClassName::Model()::where('id', $data['id'])->where('reset_code', $data['reset_code'])->first();

        if ( !$item ) {
            return 'ID and Reset Code combination is invalid.';
        }

        if ( $item->reset_code_date < date('Y-m-d H:i:s', strtotime('-5 days')) ) {
            return 'Reset Code expired.';
        }

        return true;
    }
}
