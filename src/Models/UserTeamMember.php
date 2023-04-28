<?php
namespace RA\Auth\Models;

use RA\Auth\Services\ClassName;

class UserTeamMember extends Model
{
    protected $table = 'user_team_member';

    public function team() {
        return $this->belongsTo(ClassName::Model('UserTeam'));
    }

    public function user() {
        return $this->belongsTo(ClassName::Model('User'));
    }
}
