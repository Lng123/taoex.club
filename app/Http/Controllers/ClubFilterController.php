<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Club;
use App\Clubuser;
use App\Match;
use App\MatchResult;
use App\Utility;

class ClubFilterController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
    	$date = 2018;

        //User Table
    	$user_table = new User;
        //Club Table
        $club_table = new Club;
        //Match Table
        $match_table = new Match;
        //Match Result Table
        $result_table = new MatchResult;
        
        //Obtain user ID
        $uid = Auth::user()->id;
        //Obtain club id
        $club_id = Auth::user()->club_id;
        //Obtain status
        $approved_status = Auth::user()->approved_status;
        $type = Auth::user()->type;
        $province = Auth::user()->province;
        $city = Auth::user()->city;
        
        $clubGames = $match_table->where('club_id', $club_id)->get();
        
        $club = $club_table->where('id', $club_id)->first();
        
        $clubMembers = DB::table('UserClubs')->join('users','users.id','=','UserClubs.id')->select('*')->where('UserClubs.club_id', $club_id)->get();

        $string ="";
        
        $combined = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->get();
                $clubGameCount = $match_table->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->get()->count();

        $memberData = [];
        $i = 0;

        foreach ($clubMembers as $clubMember) {
        	
        	$gameCount = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->where('player_id', $clubMember->id)->get()->count();
        	
        	$won = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->where('player_id', $clubMember->id)->where('winner_id',$clubMember->id)->get()->count();
        	
            $score = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->where('player_id', $clubMember->id)->sum('total');
            
            if ($clubGameCount == 0) {
                $rank = ($score/1) * $won;
            } else {
                $rank = ($score/$clubGameCount) * $won;
            }
        	
        	$memberData[$i]= array('name' => $clubMember->firstName. " " . $clubMember->lastName, 'role' => $clubMember->type, 'games' => $gameCount, 'won' => $won, 'score' => $score, 'rank'=>$rank);
        	$i++;

            //$string .= " id: " . $clubMember->id . " : " . $gameCount . " gamesWon: ". $won . "//\\";
        }
            return view('taoex.clubFilter', array('memberData'=>$memberData));

    }
    
    public function clubMemberRanking(Request $request)
    {
    	
	$date = $request->year;

        //User Table
    	$user_table = new User;
        //Club Table
        $club_table = new Club;
        //Match Table
        $match_table = new Match;
        //Match Result Table
        $result_table = new MatchResult;
        
        //Obtain user ID
        $uid = Auth::user()->id;
        //Obtain club id
        $club_id = Auth::user()->club_id;
        //Obtain status
        $approved_status = Auth::user()->approved_status;
        $type = Auth::user()->type;
        $province = Auth::user()->province;
        $city = Auth::user()->city;
        
        $clubGames = $match_table->where('club_id', $club_id)->get();
        
        $club = $club_table->where('id', $club_id)->first();
        
        $clubMembers = $user_table->where('club_id', $club_id)->where('approved_status', 1)->get();

        $string ="";
        
        $combined = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->get();
        
        $clubGameCount = $match_table->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->get()->count();
        $memberData = [];
        $i = 0;

        foreach ($clubMembers as $clubMember) {
        	
        	$gameCount = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->where('player_id', $clubMember->id)->get()->count();
        	
        	$won = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->where('player_id', $clubMember->id)->where('winner_id',$clubMember->id)->get()->count();
        	
        	$score = $match_table->join('MatchResult', 'Match.id', '=', 'MatchResult.match_id')->where('club_id', $club_id)->where('endDate', '>=', $date."-01-1")->where('endDate', '<=', $date."-12-31")->where('player_id', $clubMember->id)->sum('total');
        	$rank = ($score/$clubGameCount) * $won;
        	
        	
        	$memberData[$i]= array('name' => $clubMember->firstName. " " . $clubMember->lastName, 'role' => $clubMember->type, 'games' => $gameCount, 'won' => $won, 'score' => $score, 'rank'=>$rank);
        	
        	
        	$i++;

            //$string .= " id: " . $clubMember->id . " : " . $gameCount . " gamesWon: ". $won . "//\\";
        }
            return view('taoex.clubFilter', array('memberData'=>$memberData));

        //return view('taoex.redirect()->route("club")->with('memberData', $memberData);

        //return($memberData);


    }

}