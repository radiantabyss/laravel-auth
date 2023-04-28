<?php
namespace RA\Auth\Models;

use RA\Auth\Services\ClassName;

class UserInvite extends Model
{
    protected $table = 'user_invite';

    protected $hidden = [
        'code',
    ];

    public function user() {
        return $this->belongsTo(ClassName::Model('User'));
    }
}
