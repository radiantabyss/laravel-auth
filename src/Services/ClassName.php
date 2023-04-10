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

    public static function Presenter($presenter) {
        if ( class_exists('\App\Domains\Auth\Presenters\\'.$presenter) ) {
            return '\App\Domains\Auth\Presenters\\'.$presenter;
        }

        return '\RA\Auth\Domains\Auth\Presenters\\'.$presenter;
    }

    public static function Validator($validator) {
        if ( class_exists('\App\Domains\Auth\Validators\\'.$validator) ) {
            return '\App\Domains\Auth\Validators\\'.$validator;
        }

        return '\RA\Auth\Domains\Auth\Validators\\'.$validator;
    }

    public static function Transformer($transformer) {
        if ( class_exists('\App\Domains\Auth\Transformers\\'.$transformer) ) {
            return '\App\Domains\Auth\Transformers\\'.$transformer;
        }

        return '\RA\Auth\Domains\Auth\Transformers\\'.$transformer;
    }
}
