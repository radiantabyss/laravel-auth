<?php
namespace RA\Auth\Domains\Auth\Transformers;

class TeamTransformer
{
    private static $meta_fields = [];

    public static function run($data) {
        $data['user_id'] = \Auth::user()->id;

        $meta = [];
        foreach ( $data as $key => $value ) {
            if ( in_array($key, self::$meta_fields) ) {
                $meta[$key] = $value;
                unset($data[$key]);
            }
        }

        return $data;
    }
}
