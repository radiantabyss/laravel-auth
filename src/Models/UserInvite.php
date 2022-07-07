<?php
namespace RA\Auth\Models;

class UserInvite extends BaseModel
{
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    public function __construct($attributes = []) {
        parent::__construct($attributes);
        $this->table = env('RA_AUTH_TABLE_NAME').'_invite';
    }
}
