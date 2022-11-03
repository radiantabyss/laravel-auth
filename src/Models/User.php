<?php
namespace RA\Auth\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, SoftDeletes;

    protected $table = 'user';

    protected $hidden = [
        'password',
    ];

    public function loadMeta($keys = []) {
        $metas = UserMeta::where('user_id', $this->id)->get();
        $item_meta = [];
        foreach ( $metas as $meta ) {
            if ( !count($keys) ) {
                $item_meta[$meta->key] = $meta->value;
            }
            else if ( in_array($meta->key, $keys) ) {
                $item_meta[$meta->key] = $meta->value;
            }
        }

        $this->meta = $item_meta;
    }

    public static function loadMetaForMany($items, $keys = []) {
        if ( !count($items) ) {
            return $items;
        }

        $grouped_metas = UserMeta::whereIn('user_id', pluck($items))->get()->groupBy('user_id');

        foreach ( $items as $item ) {
            $metas = $grouped_metas[$item->id] ?? [];
            $item_meta = [];

            foreach ( $metas as $meta ) {
                if ( !count($keys) ) {
                    $item_meta[$meta->key] = $meta->value;
                }
                else if ( in_array($meta->key, $keys) ) {
                    $item_meta[$meta->key] = $meta->value;
                }
            }

            $item->meta = $item_meta;
        }

        return $items;
    }

    public function log($type, $message = '') {
        UserLog::create([
            'user_id' => $this->id,
            'type' => $type,
            'message' => $message,
        ]);
    }
}
