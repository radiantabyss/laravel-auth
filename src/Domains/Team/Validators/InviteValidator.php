<?php
namespace RA\Auth\Domains\Team\Validators;

use RA\Auth\Services\ClassName;

class InviteValidator
{
    public static function run($data, $id) {
        //validate request params
        $validator = \Validator::make($data, [
            'emails' => 'required',
            'role' => 'required',
        ], [
            'emails.required' => 'Emails are required.',
            'role.required' => 'Role is required.',
        ]);

        if ( $validator->fails() ) {
            return $validator->messages();
        }

        //validate role
        if ( !in_array($data['role'], config('ra-auth.allowed_team_roles')) ) {
            return 'Role is invalid.';
        }

        //validate emails
        preg_match_all("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/", $data['emails'], $matches);
        if ( !count($matches[0]) ) {
            return 'No valid emails were found.';
        }

        return true;
    }
}
