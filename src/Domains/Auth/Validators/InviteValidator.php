<?php
namespace RA\Auth\Domains\Auth\Validators;

use RA\Auth\Services\ClassName;

class InviteValidator
{
    public static function run($data) {
        //validate request params
        $validator = \Validator::make($data, [
            'id' => 'required',
            'email' => 'required',
            'role' => 'required',
        ], [
            'id.required' => 'ID is required.',
            'email.required' => 'Email is required.',
            'role.required' => 'Role is required.',
        ]);

        if ( $validator->fails() ) {
            return $validator->messages();
        }

        return true;
    }
}
