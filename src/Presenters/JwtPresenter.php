<?php
namespace RA\Auth\Presenters;

class JwtPresenter
{
    public static function run($item) {
        // return $item;
        $unwanted_fields = [
            'email', 'name', 'type', 'password', 'phone',
            'activation_code', 'reset_code', 'reset_code_date',
            'is_active', 'last_login_at', 'created_at', 'updated_at',
        ];

        foreach ( $unwanted_fields as $unwanted_field ) {
            unset($item->$unwanted_field);
        }

        return $item;
    }
}
