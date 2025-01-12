<?php
namespace RA\Auth\Models;

use RA\Auth\Services\ClassName;

class UserCode extends Model
{
    protected $table = 'user_code';

    protected $hidden = [
        'code',
    ];

    public function user() {
        return $this->belongsTo(ClassName::Model('User'));
    }
}
