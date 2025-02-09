<?php
namespace RA\Auth\Models;

use RA\Auth\Services\ClassName;

class UserMeta extends Model
{
    protected $table = 'user_meta';
    public $timestamps = false;

    public function user() {
        return $this->belongsTo(ClassName::Model('User'));
    }
}
