<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'League';

    public function league()
    {
        return $this->belongsToMany(
            'app\Match',
            'Match_League',
            'league_id',
            'match_id');
    }
}
