<?php
namespace RA\Auth\Models;

use RA\Auth\Services\ClassName;

class TeamMeta extends Model
{
    protected $table = 'team_meta';
    public $timestamps = false;

    public function team() {
        return $this->belongsTo(ClassName::Model('Team'));
    }
}
