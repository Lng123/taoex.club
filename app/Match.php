<?php
/*
 * Match table
 * This table holds basic information about matches recorded in TAOEX.
 * Controllers using the Match model:
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

class Match extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'Match';

    public function hostClub()
    {
        return $this->belongsTo('app\Club', 'club_id');
    }

    public function winner()
    {
        return $this->belongsTo('app\User', 'winner_id');
    }


    public function tournament()
    {
        return $this->belongsToMany(
            'app\Tournament',
            'Match_Tournament',
            'match_id',
            'tournament_id');
    }

    public function league()
    {
        return $this->belongsToMany(
            'app\League',
            'Match_League',
            'match_id',
            'league_id');
    }

    public function matchResult()
    {
        return $this->hasMany('App\MatchResult', 'match_id');
    }

    public function player()
    {
        return $this->belongsToMany('app\Match', 'MatchResult', 'match_id', 'player_id');
    }
}