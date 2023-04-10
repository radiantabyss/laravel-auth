<?php
namespace RA\Auth\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

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
}
