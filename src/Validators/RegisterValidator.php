<?php
namespace RA\Auth\Validators;

use RA\Auth\Models\User as Model;

class RegisterValidator
{
    public static function run($data) {
        //validate request params
        $validator = \Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'password.required' => 'Password is required',
        ]);

        if ( $validator->fails() ) {
            return $validator->messages();
        }

        //check if user already exists
        $item = Model::where('email', trim($data['email']))->first();

        if ( $item ) {
            return 'An user with this email already exists.';
        }

        return true;
    }
}
