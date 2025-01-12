<?php
namespace RA\Auth\Domains\Team\Validators;

use RA\Auth\Services\ClassName;

class ChangeRoleValidator
{
    public static function run($data, $team_id) {
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

        $item = ClassName::Model('TeamMember')::where('id', $data['id'])
            ->where('team_id', $team_id)
            ->where('role', '!=', 'owner')
            ->first();

        if ( !$item ) {
            return 'Team Member not found.';
        }

        return true;
    }
}
