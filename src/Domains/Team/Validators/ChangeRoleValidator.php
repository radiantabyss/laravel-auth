<?php
namespace RA\Auth\Domains\Team\Validators;

use RA\Auth\Services\ClassName;

class ChangeRoleValidator
{
    public static function run($data) {
        //validate request params
        $validator = \Validator::make($data, [
            'id' => 'required',
            'role' => 'required',
        ], [
            'id.required' => 'ID is required',
            'role.required' => 'Role is required',
        ]);

        if ( $validator->fails() ) {
            return $validator->messages();
        }

        if ( \Gate::denies('manage-team', $data['team_id']) ) {
            return 'Sorry, you can\'t change this team member.';
        }

        $item = ClassName::Model('TeamMember')::where('id', $data['id'])
            ->where('team_id', $data['team_id'])
            ->where('role', '!=', 'owner')
            ->first();

        if ( !$item ) {
            return 'Team Member not found.';
        }

        return true;
    }
}
