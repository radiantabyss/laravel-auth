<?php
namespace RA\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function __construct($attributes = []) {
        parent::__construct($attributes);
        $this->connection = env('RA_AUTH_DB_CONNECTION');
    }
}
