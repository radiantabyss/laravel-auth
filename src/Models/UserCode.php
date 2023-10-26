<?php
namespace Lumi\Auth\Models;

use Lumi\Auth\Services\ClassName;

class UserCode extends Model
{
    protected $table = 'user_code';

    protected $hidden = [
        'code',
    ];

    public function user() {
        return $this->belongsTo(ClassName::Model('User'));
    }
}
