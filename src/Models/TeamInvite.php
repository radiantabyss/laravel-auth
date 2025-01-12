<?php
namespace RA\Auth\Models;

use RA\Auth\Services\ClassName;

class TeamInvite extends Model
{
    protected $table = 'team_invite';

    protected $hidden = [
        'code',
    ];

    public function user() {
        return $this->belongsTo(ClassName::Model('User'));
    }

    public function team() {
        return $this->belongsTo(ClassName::Model('Team'));
    }
}
