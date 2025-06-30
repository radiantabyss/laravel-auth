<?php
namespace RA\Auth\Domains\Team\Transformers;

use RA\Auth\Services\ClassName;

class PatchTransformer
{
    private static $allowed_fields = [
        'name',
    ];

    private static $allowed_meta_fields = [
        'image_path',
    ];

    public static function run($data) {
        $data = self::filterFields($data);
        $data = self::filterMetaFields($data);

        return $data;
    }

    private static function filterFields($data) {
        foreach ( $data as $key => $value ) {
            if ( $key != 'meta' && !in_array($key, self::$allowed_fields) ) {
                unset($data[$key]);
            }
        }

        return $data;
    }

    private static function filterMetaFields($data) {
        if ( !isset($data['meta']) ) {
            return $data;
        }

        $meta = $data['meta'];

        foreach ( $meta as $key => $value ) {
            if ( !in_array($key, self::$allowed_meta_fields) ) {
                unset($meta[$key]);
            }
        }

        $data['meta'] = $meta;

        return $data;
    }
}
