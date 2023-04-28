<?php
namespace RA\Auth\Domains\User\Validators;

use RA\Auth\Services\ClassName;

class PatchValidator
{
    public static function run($data) {
        //check if email taken
        if ( isset($data['email']) && $data['email'] != \Auth::user()->email ) {
            $exists = ClassName::Model('User')::where('email', $data['email'])->exists();
            if ( $exists ) {
                return 'An user with this email already exists.';
            }
        }

        if ( isset($data['password']) ) {
            $item = ClassName::Model('User')::find(\Auth::user()->id);

            if ( !\Hash::check($data['current_password'], $item->password) ) {
                return 'Current password is incorrect.';
            }
        }

        return true;
    }
}
