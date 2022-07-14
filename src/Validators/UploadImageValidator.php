<?php
namespace RA\Auth\Validators;

class UploadImageValidator
{
    public static function run($data) {
        //validate request params
        $validator = \Validator::make($data, [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
        ], [
            'file.required' => 'Image is required',
            'file.image' => 'Only images are allowed',
            'file.mimes' => 'Only images are allowed',
            'file.max' => 'Max size was exceeded. 5MB is the limit.',
        ]);

        if ( $validator->fails() ) {
            return $validator->messages();
        }

        return true;
    }
}
