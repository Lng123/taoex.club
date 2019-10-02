<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournament extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'Tournament';


    public function club()
    {
        return $this->belongsTo('app\Club', 'club_id');
    }

    public function match()
    {
        return $this->belongsToMany(
            'app\Match',
            'Match_Tournament',
            'tournament_id',
            'match_id');
    }
}
