<?php
namespace RA\Auth\Models;

use Illuminate\Database\Eloquent\Model as LaravelModel;

class BaseModel extends LaravelModel
{
    protected $guarded = [
        'id', 'created_at', 'updated_at',
    ];

    public function __construct($attributes = []) {
        parent::__construct($attributes);
        $this->connection = env('RA_AUTH_DB_CONNECTION', 'mysql');
    }
}
