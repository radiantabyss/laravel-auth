<?php
namespace Lumi\Auth\Domains\Team\Validators;

use Lumi\Auth\Services\ClassName;

class Validator
{
    public static function run($data, $team_id = false) {
        //check if item exists
        if ( $team_id ) {
            $item = ClassName::Model('Team')::find($team_id);
            if ( !$item ) {
                return 'Team not found.';
            }
        }

        //validate request params
        $validator = \Validator::make($data, [
            'name' => 'required',
        ], [
            'name.required' => 'Name is required',
        ]);

        if ( $validator->fails() ) {
            return $validator->messages();
        }

        //validate image
        $validation = self::image($data);
        if ( $validation !== true ) {
            return $validation;
        }

        return true;
    }

    private static function image($data) {
        if ( !isset($data['image']) ) {
            return true;
        }

        //validate request params
        $validator = \Validator::make($data, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ], [
            'image.image' => 'Only images are allowed',
            'image.mimes' => 'Only images are allowed',
            'image.max' => 'Max size was exceeded. 5MB is the limit.',
        ]);

        if ( $validator->fails() ) {
            return $validator->messages();
        }

        return true;
    }
}
