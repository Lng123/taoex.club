<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRank extends Model
{
    protected $table = 'UserRank';
    protected $primaryKey = 'player_id';
}