<?php
namespace RA\Auth\Transformers;

class PatchTransformer
{
    public static function run($data) {
        //filter fields
        foreach ( $data as $key => $value ) {
            if ( !in_array($key, config('ra-auth.patch_allowed_fields')) ) {
                unset($data[$key]);
            }
        }

        //update name
        if ( isset($data['name']) ) {
            $exp = explode(' ', ucwords($data['name']));
            $last_name = array_pop($exp);
            $first_name = implode(' ', $exp);

            if ( !$first_name ) {
                $first_name = $last_name;
                $last_name = '';
            }

            $data['first_name'] = $first_name;
            $data['last_name'] = $last_name;
        }

        //update password
        if ( isset($data['password']) ) {
            $data['password'] = app('hash')->make($data['password']);
        }

        return $data;
    }
}
