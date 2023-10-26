<?php
namespace Lumi\Auth\Domains\User\Validators;

use Lumi\Auth\Services\ClassName;

class ResetPasswordValidator
{
    public static function run($data) {
        //validate request params
        $validator = \Validator::make($data, [
            'code' => 'required',
            'password' => 'required',
        ], [
            'code.required' => 'Reset Code is required',
            'password.required' => 'Password is required',
        ]);

        if ( $validator->fails() ) {
            return $validator->messages();
        }

        //check code
        $code = ClassName::Model('UserCode')::where('type', 'reset_password')
            ->where('code', $data['code'])
            ->where('expires_at', '>=', date('Y-m-d H:i:s'))
            ->first();

        if ( !$code ) {
            return 'Reset Code combination is invalid or has expired.';
        }

        //check user
        $item = ClassName::Model('User')::find($code->user_id);

        if ( !$item ) {
            return 'Reset Code combination is invalid or has expired.';
        }

        return true;
    }
}
