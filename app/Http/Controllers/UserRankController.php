<?php

namespace App\Http\Controllers;

use App\Match;
use App\MatchResult;
use App\User;
use App\Club;
use App\Clubuser;
use App\UserRank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	//assign table variables
        $user_table = new User;
        $club_table = new Club;
        $clubuser_table = new Clubuser;
        $match_table = new Match;
        $result_table = new MatchResult;
        
        $uid = Auth::user()->id;
        $club_id = Auth::user()->club_id;
        
        $string = "";
        
        $users = $user_table->get();
        
        //total matches
        $totalMatchCount = $match_table->groupBy('id')->get()->count();

	
        foreach ($users as $user) {
           $gameRatio = 0;
           $userRank = new UserRank;
           
           $userRank->player_id = $user->id;
           
           $gameCount = $result_table->where('player_id',$user->id)->get()->count();
           $userRank->gamesPlayed = $gameCount;
           
           $gamesWon = $match_table->where('winner_id', $user->id)->get()->count();
           $userRank->win = $gamesWon;
           
           $gamesLost = $gameCount - $gamesWon;
           $userRank->lose = $gamesLost;

           if ($gameCount != 0) {
            //$gameRatio = ($gamesWon / $gamesLost) / $gameCount;
            $gameRatio = (($user->score)/$totalMatchCount) * $gamesWon;

           }
           
           $userRank->ratio = $gameRatio;
           
           $userRank->totalScore = $user->score;
           $userRank->save();
           $string .= "[ player id: ". $user->id . " played " . $gameCount . " games, won ". $gamesWon . ", lost " . $gamesLost . " and has a ratio of ". $gameRatio . " ] \n";
           //$userRank->gamesPlayed;
           //$userRank->win;
           //$userRank->lose;
           //$userRank->ratio;
           //$userRank->totalScore;
        }
        return ($string);
    }
}