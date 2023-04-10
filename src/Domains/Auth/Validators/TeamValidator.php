<?php
namespace RA\Auth\Domains\Auth\Validators;

use RA\Auth\Services\ClassName;

class TeamValidator
{
    public static function run($data, $id = false) {
        //check if item exists
        if ( $id ) {
            $item = ClassName::Model('UserTeam')::find($id);
            if ( !$item ) {
                return \Domain::name().' not found.';
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
