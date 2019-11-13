<?php

/*
 * User table
 * This table holds user information.
 * 
 * Controllers using the User model:
 * Controllers\...
 * - Auth\RegisterController.php
 * - Auth\UsersController.php
 * - ApplyMatchController.php
 * - ClubController.php
 * - ClubFilterController.php
 * - ClubRankController.php
 * - GuestController.php
 * - HomeController.php
 * - MatchController.php
 * - RankingController.php
 * - UserRankController.php
 * 
 * Views using the User model:
 * Resources\Views\....blade.php
 * - Layouts\app
 * - TAOEX\applyForClub
 * - TAOEX\club
 * - TAOEX\editClubProfile
 * - home
 */


namespace App;



use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Support\Facades\DB;

use App\Club;



class User extends Authenticatable

{

    use Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [

        'id','firstName', 'lastName', 'phone', 'address', 'country', 'province', 'city', 'type', 'opt', 'email', 'password', 'approved_status','image','image_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    protected $hidden = [
        'password', 'remember_token',
    ];


    public function ownedClub()
    {
        return $this->hasOne('app\Club', 'owner_id');
    }



    public function club()
    {
        return $this->belongsTo('app\User', 'user_id');
    }



    public function assistingClub()
    {
        return $this->belongsToMany('app\User', 'ClubAssistant', 'assistant_id', 'club_id');
    }



    public function winMatch()
    {
        return $this->hasMany('app\Match', 'winner_id');
    }



    public function matchResult()
    {
        return $this->hasMany('app\MatchResult', 'player_id');
    }



    public function match()
    {
        return $this->belongsToMany('app\Match', 'MatchResult', 'player_id', 'match_id');
    }



    public static function updateUserToClubOwner($uid, $club_id) {
        DB::table('users')->where('id', $uid)->update(['type'=>1, 'approved_status'=>1, 'club_id'=>$club_id]);
    }

    public static function isBanned(){
        return (DB::table('banned_users')->where('banned_id', auth()->user()->id)->first());
    }

}