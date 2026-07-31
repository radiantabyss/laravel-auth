<?php
namespace RA\Auth\Events;

use Illuminate\Foundation\Events\Dispatchable;

class TeamDeleted
{
    use Dispatchable;

    public $team;

    public function __construct($team) {
        $this->team = $team;
    }
}
