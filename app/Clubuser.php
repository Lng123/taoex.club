<?php

/*
 * ClubUser table
 * This table holds membership relationships between a user and a club.
 * Controllers using the Club model:
 * Controllers\...
 * - ClubController.php
 * - ClubFilterController.php
 * - ClubRankController.php
 * - HomeController.php
 * - UserRankController.php
 */
 
namespace App;

use Illuminate\Database\Eloquent\Model;

class Clubuser extends Model
{
    protected $primaryKey = 'club_id';
    protected $table = 'ClubUser';

    public function club()
    {
    	return $this->belongTo('App\Club', 'id');
    }
}