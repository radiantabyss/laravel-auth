<?php
namespace Lumi\Auth\Models;

use Lumi\Auth\Services\ClassName;

class UserLog extends Model
{
    protected $table = 'user_log';

    public function user() {
        return $this->belongsTo(ClassName::Model('User'));
    }
}
