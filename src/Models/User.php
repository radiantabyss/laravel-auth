<?php
namespace RA\Auth\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use RA\Auth\Services\ClassName;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $table = 'user';

    protected $hidden = [
        'password',
    ];

    public function log($type, $message = '') {
        $country = '';
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'];

        if ( config('ra-auth.country_guesser_installed') ) {
            try {
                $geoip = new \GeoIp2\Database\Reader(storage_path().'/geoip/GeoLite2-Country/GeoLite2-Country.mmdb');
                $geoip_record = toArray($geoip->country($ip));
                $country = $geoip_record['country']['iso_code'];
            }
            catch(\Exception $e) {}
        }

        ClassName::Model('UserLog')::create([
            'user_id' => $this->id,
            'type' => $type,
            'message' => \Str::limit($message, 500),
            'device' => \Str::limit($_SERVER['HTTP_USER_AGENT'], 500),
            'ip' => $ip,
            'country' => $country,
        ]);
    }

    public function loadMeta($keys = []) {
        $metas = ClassName::Model('UserMeta')::where('user_id', $this->id)->get();
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

        $grouped_metas = ClassName::Model('UserMeta')::whereIn('user_id', pluck($items))->get()->groupBy('user_id');

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
}
