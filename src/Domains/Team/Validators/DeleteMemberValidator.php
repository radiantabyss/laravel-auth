<?php
namespace Lumi\Auth\Domains\Team\Validators;

class DeleteMemberValidator
{
    public static function run($item) {
        if ( !$item ) {
            return 'Team Member not found.';
        }

        if ( \Gate::denies('manage-team', $item->team_id) ) {
            return 'Sorry, you can\'t delete this team member.';
        }

        if ( !in_array($item->role, config('ra-auth.allowed_team_roles')) ) {
            return 'This member can\'t be deleted.';
        }

        return true;
    }
}
