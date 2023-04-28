<?php
namespace RA\Auth\Domains\User\Validators;

use RA\Auth\Services\ClassName;

class AcceptInviteValidator
{
    public static function run($data) {
        //check if invite exists and not expired
        $invite = ClassName::Model('UserInvite')::where('code', $data['code'])
            ->where('expires_at', '>=', date('Y-m-d H:i:s'))
            ->first();

        if ( !$invite ) {
            return 'Invite code is invalid or has expired.';
        }

        if ( \Auth::check() ) {
            //validate request params
            $validator = \Validator::make($data, [
                'code' => 'required',
            ], [
                'code.required' => 'Code is required',
            ]);

            //check if email is correct
            if ( $invite->email != \Auth::user()->email ) {
                return 'Email is incorrect.';
            }
        }
        else {
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
            $user = ClassName::Model('User')::where('email', trim($data['email']))->first();

            //check if password if correct
            if ( !\Hash::check($data['password'], $user->passowrd) ) {
                return 'A user with this email already exists.';
            }

            //check if email is correct
            if ( $invite->email != $data['email'] ) {
                return 'Email is incorrect.';
            }
        }

        return true;
    }
}
