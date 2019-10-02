<?php
/*
 * MatchResult table
 * This table holds the relationship between a match (result) and a user.
 * Controllers using the MatchResult model:
 * Controllers\...
 * - ApplyMatchController.php
 * - ClubController.php
 * - ClubFilterController.php
 * - ClubRankController.php
 * - GuestController.php
 * - HomeController.php
 * - MatchController.php
 * - RankingController.php
 * - UserRankController.php
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

class MatchResult extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'MatchResult';

    public function player()
    {
        return $this->belongsTo('app\User', 'player_id');
    }

    public function match()
    {
        return $this->belongsTo('app\Match', 'match_id');
    }

}