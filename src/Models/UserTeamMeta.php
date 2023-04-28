<?php
namespace RA\Auth\Models;

use RA\Auth\Services\ClassName;

class UserTeamMeta extends Model
{
    protected $table = 'user_team_meta';

    public function team() {
        return $this->belongsTo(ClassName::Model('UserTeam'));
    }
}
