<?php
namespace RA\Auth\Validators;

use RA\Auth\Services\ClassName;

class LoginValidator
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

        //get user
        $item = ClassName::Model()::where('email', trim($data['email']))->first();

        //check if user exists
        if ( !$item ) {
            return 'Email and password combination is not valid.';
        }

        //check if user has been activated
        if ( config('ra-auth.activation_required') && $item->activation_code ) {
            return 'This account hasn\'t been activated yet.';
        }

        //check if user is active
        if ( !$item->is_active ) {
            return 'This account isn\'t active anymore.';
        }

        //verify password
        if ( !app('hash')->check($data['password'], $item->password) ) {
            return 'Email and password combination is not valid.';
        }

        return true;
    }
}
