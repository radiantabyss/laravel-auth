<?php
namespace RA\Auth\Validators;

use RA\Auth\Services\ClassName;

class AcceptInviteValidator
{
    public static function run($data) {
        //validate request params
        $validator = \Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
            'code' => 'required',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'password.required' => 'Password is required',
            'code.required' => 'Code is required',
        ]);

        if ( $validator->fails() ) {
            return $validator->messages();
        }

        //check if user already exists
        $item = ClassName::Model()::where('email', trim($data['email']))->first();

        if ( $item ) {
            return 'A user with this email already exists.';
        }

        return true;
    }
}
