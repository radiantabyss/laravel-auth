<?php
namespace RA\Auth\Validators;

class ConfirmPhoneValidator
{
    public static function run($data) {
        //validate request params
        $validator = \Validator::make($data, [
            'code' => 'required',
        ], [
            'code.required' => 'Code is required',
        ]);

        if ( $validator->fails() ) {
            return $validator->messages();
        }

        if ( \Auth::user()->phone_validation_code != $data['code'] ) {
            return 'Code is invalid.';
        }

        return true;
    }
}
