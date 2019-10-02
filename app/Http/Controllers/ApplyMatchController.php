<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Match;
use App\Club;
use App\User;
use App\MatchResult;
use Illuminate\Support\Facades\DB;

class ApplyMatchController extends Controller
{
    //


    public function index()
    {
    	return view('taoex.applyNewMatch');
    }

    public function apply(Request $request)
    {
    	$name = $request->name;
    	$address = $request->address;
    	$startDate = $request->startDate;
    	$endDate = $request->endDate;
    	$start_time= $request->start_time;
    	        $approved_status = Auth::user()->approved_status;

    	    	$uid = Auth::user()->id;
    	$club_id = Auth::user()->club_id;
    	$user_table = new User;
    	$result_table = new MatchResult;
        $club_table = new Club;

    	$match_table = new Match;
    	$match_table->name = $name;
    	$match_table->address = $address;
    	$match_table->startDate = $startDate;
    	$match_table->endDate = $startDate;
    	$match_table->start_time = $start_time;
    	$match_table->club_id = $club_id;

    	$match_table->save();
	
	$total_score = $result_table->where('player_id', $uid)->sum('total');

        $ranking = $user_table->where('score','>=', $total_score)->get()->count();
        
        $userMessages = DB::table('messages')
                            ->select('message', 'message_id')
                            ->get();
        $club_table = new Club;


      $club = $club_table->where('id', $club_id)->first();
            $clubMembers = $user_table->where('club_id', $club_id)->where('approved_status', 1)->get();
            $nearPlayers = $user_table->where('approved_status', 0)->get();
            $allPlayers = $user_table->where('id', '!=', Null)->get();
            $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();


            $clubOwner = $user_table->where('club_id', $club_id)->where('approved_status', $approved_status)->where('type', 1)->first();

            $numberMembers = $user_table->where('club_id', $club_id)->where('approved_status', 1)->count();
            
            $clubGames = $match_table->where('club_id', $club_id)->get();
        
        $club = $club_table->where('id', $club_id)->first();
        
        $allMatches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->get();
 $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
        	$total_score = $result_table->where('player_id', $uid)->sum('total');

        $createSuccess = 1;

            return view('taoex.club', array('club'=>$club, 'clubMembers'=>$clubMembers, 'matches'=>$matches, 'allPlayers'=>$allPlayers, 'numberMembers'=>$numberMembers, 'allMatches'=>$allMatches, 'clubOwner'=>$clubOwner, 'totalScore'=>$totalScore, 'createSuccess'=>$createSuccess));
    }


    public function record(Request $request)
    {
        $club_table = new Club;

        $result_table = new MatchResult;
	$uid = Auth::user()->id;
        $club_id = Auth::user()->club_id;
        $approved_status = Auth::user()->approved_status;
    	$HOK = 5;
        $ELI = 2;
        $CAP = 1;
        $LEB = 5;
        $ART = 10;
        $WIN = 6;
        $match_result = new MatchResult;
        $match_table = new Match;

        $match_id = $request->match_id;
        $player_id = $request->player_id;
        $numberPlayers = $request->numberPlayers;

        $elimination = $request->elimination;
        $capture = $request->capture;
        $hook = $request->hook;
        $winBonus = $request->winBonus;

        if ($winBonus == 0) {
            $total = $HOK * $hook + $capture * $CAP + $elimination * $ELI;
            $winBonusR = 0;
        } else if ($winBonus == 5) {
            $total = $HOK * $hook + $capture * $CAP + $elimination * $ELI
                        + $LEB*$numberPlayers + 6 * $numberPlayers;
            $winBonusR = $LEB*$numberPlayers;
            $match_table->where('id', $match_id)->update(['winner_id'=>$player_id]);
        } else if($winBonus == 6) {
            $total = $HOK * $hook + $capture * $CAP + $elimination * $ELI
                        + 6 * $numberPlayers;
            $winBonusR =  6 * $numberPlayers;
            $match_table->where('id', $match_id)->update(['winner_id'=>$player_id]);
        } else {
            $total = $HOK * $hook + $capture * $CAP + $elimination * $ELI
                        + $ART + 6 * $numberPlayers;
            $winBonusR = $ART;
            $match_table->where('id', $match_id)->update(['winner_id'=>$player_id]);
        }

        $check = $match_result->where('player_id', $player_id)->where('match_id', $match_id)->get()->count();
        $totalScore = DB::table('MatchResult')->where('player_id', Auth::user()->id)->sum('total');
       
        $match_result->match_id = $match_id;
        $match_result->player_id = $player_id;
        $match_result->elimination = $elimination;
        $match_result->capture = $capture;
        $match_result->hook = $hook;
        $match_result->winBonus = $winBonusR;
        $match_result->total = $total;
        $match_result->place = 0;

        $match_result->save();
        

    	$user_table = new User;

	$total_score = $result_table->where('player_id', $uid)->sum('total');

        $ranking = $user_table->where('score','>=', $total_score)->get()->count();
        
        $userMessages = DB::table('messages')
                            ->select('message', 'message_id')
                            ->get();


        $totalScore = DB::table('MatchResult')->where('player_id', $uid)->sum('total');
	$matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();

            $matches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->take(3)->get();
        $results = $result_table->join('users', 'player_id', '=', 'users.id')->select('users.firstName', 'users.lastName', 'MatchResult.*')->get();
        	$clubMembers = $user_table->get();
    	
    	$club = $club_table->where('id', $club_id)->first();
    	 $numberMembers = $user_table->where('club_id', $club_id)->where('approved_status', 1)->count();
            $allPlayers = $user_table->where('id', '!=', Null)->get();
            $clubMembers = $user_table->where('club_id', $club_id)->where('approved_status', 1)->get();
        $allMatches = $match_table->where('club_id', $club_id)->orderBy('endDate', 'desc')->get();
            $clubOwner = $user_table->where('club_id', $club_id)->where('approved_status', $approved_status)->where('type', 1)->first();
	$total_score = $result_table->where('player_id', $uid)->sum('total');

    	$recordSuccess = 1;
    	
    	return view('taoex.club', array('club'=>$club, 'clubMembers'=>$clubMembers, 'matches'=>$matches, 'allPlayers'=>$allPlayers, 'numberMembers'=>$numberMembers, 'allMatches'=>$allMatches, 'clubOwner'=>$clubOwner, 'totalScore'=>$totalScore, 'recordSuccess'=>$recordSuccess));
        }

              
}