<?php
namespace RA\Auth\Models;

use RA\Auth\Services\ClassName;

class TeamMember extends Model
{
    protected $table = 'team_member';

    public function team() {
        return $this->belongsTo(ClassName::Model('Team'));
    }

    public function user() {
        return $this->belongsTo(ClassName::Model('User'));
    }
}
