<?php
namespace RA\Auth\Domains\Team\Validators;

class DeleteMemberValidator
{
    public static function run($item) {
        if ( !$item ) {
            return 'Team Member not found.';
        }

        if ( !in_array($item->role, config('ra-auth.allowed_team_roles')) ) {
            return 'This member can\'t be deleted.';
        }

        return true;
    }
}
