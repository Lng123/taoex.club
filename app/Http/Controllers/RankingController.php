<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Match;
use App\MatchResult;
use App\User;
use App\Club;

class RankingController extends Controller
{
    public function index() {
        $match_table = new Match;
        $result_table = new MatchResult;
        $user_table = new User;
        $club_table = new Club;
        $club_count = Club::count();
        $clubs = $club_table->join('users', 'owner_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'Club.*')->get();
        
        $rankings = $user_table->orderBy('score','desc')->get();
        $playerCount = User::count();



        return view('taoex.ranking')->with(['club_count'=> $club_count,
                                          'clubs' => $clubs, 'ranking'=> $rankings, 'player_count'=> $playerCount]);
    }
}