<?php
namespace RA\Auth\Services;

class ClassName
{
    public static function Model($model) {
        if ( class_exists('\App\Models\\'.$model) ) {
            return '\App\Models\\'.$model;
        }

        return '\RA\Auth\Models\\'.$model;
    }

    public static function Presenter($name) {
        $exp = explode('\\', $name);
        $namespace = $exp[0];
        $name = $exp[1];

        if ( class_exists('\App\Domains\Auth\\'.$namespace.'\\Presenters\\'.$name) ) {
            return '\App\Domains\Auth\\'.$namespace.'\\Presenters\\'.$name;
        }

        return '\RA\Auth\Domains\\'.$namespace.'\\Presenters\\'.$name;
    }

    public static function Transformer($name) {
        $exp = explode('\\', $name);
        $namespace = $exp[0];
        $name = $exp[1];

        if ( class_exists('\App\Domains\Auth\\'.$namespace.'\\Transformers\\'.$name) ) {
            return '\App\Domains\Auth\\'.$namespace.'\\Transformers\\'.$name;
        }

        return '\RA\Auth\Domains\\'.$namespace.'\\Transformers\\'.$name;
    }

    public static function Validator($name) {
        $exp = explode('\\', $name);
        $namespace = $exp[0];
        $name = $exp[1];

        if ( class_exists('\App\Domains\Auth\\'.$namespace.'\\Validators\\'.$name) ) {
            return '\App\Domains\Auth\\'.$namespace.'\\Validators\\'.$name;
        }

        return '\RA\Auth\Domains\\'.$namespace.'\\Validators\\'.$name;
    }
}
