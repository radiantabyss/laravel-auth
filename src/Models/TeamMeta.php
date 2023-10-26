<?php
namespace Lumi\Auth\Models;

use Lumi\Auth\Services\ClassName;

class TeamMeta extends Model
{
    protected $table = 'team_meta';
    public $timestamps = false;

    public function team() {
        return $this->belongsTo(ClassName::Model('Team'));
    }
}
