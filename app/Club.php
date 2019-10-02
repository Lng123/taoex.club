<?php

/*
 * Club table
 * This table holds basic information about clubs in TAOEX.
 * Controllers using the Club model:
 * Controllers\...
 * - ApplyMatchController.php
 * - ClubController.php
 * - ClubFilterController.php
 * - ClubRankController.php
 * - GuestController.php
 * - HomeController.php
 * - RankingController.php
 * - UserRankController.php
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'Club';
    protected $fillable = [
        'name','image', 'image_type', 'owner_id','city','province','comment'
    ];

    public function owner()
    {
        return $this->belongsTo('app\User', 'owner_id');
    }

    public function assistant()
    {
        return $this->belongsTo('app\User', 'assistant_id');
    }

    public function tournament()
    {
        return $this->hasMany('App\Tournament', 'club_id');
    }

    public function match()
    {
        return $this->hasMany('App\Match', 'club_id');
    }

    public function user()
    {
        return $this->belongsTo('app\Club', 'user_id');
    }

    public function clubuser()
    {
        return $this->hasMany('App\clubuser', 'club_id');
    }

}