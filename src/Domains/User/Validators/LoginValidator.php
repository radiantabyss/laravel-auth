<?php
namespace Lumi\Auth\Domains\User\Validators;

use Lumi\Auth\Services\ClassName;

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
        $item = ClassName::Model('User')::where('email', trim($data['email']))->first();

        //check if user exists
        if ( !$item ) {
            return 'Email and password combination is not valid.';
        }

        //check if user has been activated
        $code = ClassName::Model('UserCode')::where('user_id', $item->id)
            ->where('type', 'confirm')
            ->first();

        if ( config('lumi-auth.activation_required') && $code ) {
            return 'This account hasn\'t been activated yet.';
        }

        //check if user is active
        if ( !$item->is_active ) {
            return 'This account isn\'t active anymore.';
        }

        //verify password
        if ( !\Hash::check($data['password'], $item->password) ) {
            return 'Email and password combination is not valid.';
        }

        return true;
    }
}
