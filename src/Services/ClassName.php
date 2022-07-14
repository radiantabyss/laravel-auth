<?php
namespace RA\Auth\Services;

class ClassName
{
    public static function Model() {
        if ( class_exists('\App\Models\User') ) {
            return '\App\Models\User';
        }

        return '\RA\Auth\Models\User';
    }

    public static function InviteModel() {
        if ( class_exists('\App\Models\UserInvite') ) {
            return '\App\Models\UserInvite';
        }

        return '\RA\Auth\Models\UserInvite';
    }

    public static function LogModel() {
        if ( class_exists('\App\Models\UserLog') ) {
            return '\App\Models\UserLog';
        }

        return '\RA\Auth\Models\UserLog';
    }

    public static function MetaModel() {
        if ( class_exists('\App\Models\UserMeta') ) {
            return '\App\Models\UserMeta';
        }

        return '\RA\Auth\Models\UserMeta';
    }

    public static function TokenModel() {
        if ( class_exists('\App\Models\UserToken') ) {
            return '\App\Models\UserToken';
        }

        return '\RA\Auth\Models\UserToken';
    }

    public static function Presenter() {
        if ( class_exists('\App\Domains\Auth\Presenter') ) {
            return '\App\Domains\Auth\Presenter';
        }

        if ( class_exists('\App\Domains\Auth\Presenters\Presenter') ) {
            return '\App\Domains\Auth\Presenters\Presenter';
        }

        return '\RA\Auth\Presenters\UserPresenter';
    }

    public static function RegisterValidator() {
        if ( class_exists('\App\Domains\Auth\RegisterValidator') ) {
            return '\App\Domains\Auth\RegisterValidator';
        }

        if ( class_exists('\App\Domains\Auth\Validators\RegisterValidator') ) {
            return '\App\Domains\Auth\Validators\RegisterValidator';
        }

        return '\RA\Auth\Validators\RegisterValidator';
    }

    public static function RegisterTransformer() {
        if ( class_exists('\App\Domains\Auth\RegisterTransformer') ) {
            return '\App\Domains\Auth\RegisterTransformer';
        }

        if ( class_exists('\App\Domains\Auth\Transformers\RegisterTransformer') ) {
            return '\App\Domains\Auth\Transformers\RegisterTransformer';
        }

        return '\RA\Auth\Validators\RegisterTransformer';
    }

    public static function PatchValidator() {
        if ( class_exists('\App\Domains\Auth\PatchValidator') ) {
            return '\App\Domains\Auth\PatchValidator';
        }

        if ( class_exists('\App\Domains\Auth\Validators\PatchValidator') ) {
            return '\App\Domains\Auth\Validators\PatchValidator';
        }

        return '\RA\Auth\Validators\PatchValidator';
    }

    public static function PatchTransformer() {
        if ( class_exists('\App\Domains\Auth\PatchTransformer') ) {
            return '\App\Domains\Auth\PatchTransformer';
        }

        if ( class_exists('\App\Domains\Auth\Transformers\PatchTransformer') ) {
            return '\App\Domains\Auth\Transformers\PatchTransformer';
        }

        return '\RA\Auth\Validators\PatchTransformer';
    }
}
