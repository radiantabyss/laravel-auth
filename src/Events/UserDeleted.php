<?php
namespace RA\Auth\Events;

use Illuminate\Foundation\Events\Dispatchable;

class UserDeleted
{
    use Dispatchable;

    public $user;

    public function __construct($user) {
        $this->user = $user;
    }
}
