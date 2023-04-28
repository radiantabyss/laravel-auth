<?php
namespace RA\Auth\Models;

use RA\Auth\Services\ClassName;

class UserLog extends Model
{
    protected $table = 'user_log';

    public function user() {
        return $this->belongsTo(ClassName::Model('User'));
    }
}
